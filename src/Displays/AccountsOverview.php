<?php

namespace Drupal\financials\Displays;

use Drupal\financials\FinancialsUtils;
use Drupal\financials\Entity\AccountNode;

/**
 * Class AccountsOverview
 * @package Drupal\financials\Pages
 */
class AccountsOverview {
  protected $netStarting = 0;
  protected $netCurrent = 0;
  protected $netDiff = 0;

  public function allAccounts() {
    return array(
      '#theme' => 'table',
      '#header' => $this->tableHeaders(),
      '#rows' => $this->tableRows(),
      '#empty' => t('No accounts added yet.'),
      '#sticky' => true,
      '#attributes' => array(
        'class' => array('financials-accounts-overview')
      ),
    );
  }

  protected function queryNodes() {
    $query = new \EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'finance_account')
      ->propertyCondition('uid', $GLOBALS['user']->uid);
    $result = $query->execute();

    if (isset($result['node'])) {
      return array_keys($result['node']);
    }
    else {
      return array();
    }
  }

  protected function tableHeaders() {
    return array(
      t('Account name'),
      t('Starting Balance'),
      t('Current Balance'),
      t('Net difference'),
    );
  }

  protected function tableRows() {
    $rows = array();
    foreach ($this->queryNodes() as $nid) {
      $account = new AccountNode(AccountNode::loadEntity($nid));

      $startingBalance = $account->getStartingBalance();
      $this->netStarting += $startingBalance;

      $currentBalance = $account->getCurrentBalance();
      //$currentBalanceValue = FinancialsUtils::priceFieldAmount($currentBalance);
      $this->netCurrent += $currentBalance;

      $balanceDiff = ($startingBalance - $currentBalance) * -1;
      $this->netDiff += $balanceDiff;

      $accountType = $account->getAccountType();
      $diffStanding = FinancialsUtils::diffGoodOrBad($accountType, $balanceDiff);

      $rows[] = array(
        'data' => array(
          l($account->label(), $account->getPath()),
          FinancialsUtils::currencyFormat($startingBalance),
          FinancialsUtils::currencyFormat($currentBalance),
          FinancialsUtils::currencyFormat($balanceDiff),
        ),
        'class' => array(
          'financials-overview-account-row',
          ($diffStanding) ? 'ok' : 'error',
        )
      );
    }
    $rows = $this->appendStats($rows);

    return $rows;
  }

  protected function appendStats($rows) {
    if (count($rows)) {
      $rows[] = array(
        'data' => array(
          t('Net Totals'),
          FinancialsUtils::currencyFormat($this->netStarting),
          FinancialsUtils::currencyFormat($this->netCurrent),
          '',
        ),
        'class' => array(
          'info'
        )
      );
    }

    return $rows;
  }
}