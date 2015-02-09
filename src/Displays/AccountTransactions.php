<?php

namespace Drupal\financials\Displays;

use Drupal\financials\Entity\TransactionLineItem;
use Drupal\financials\FinancialsUtils;

class AccountTransactions {
  protected $accountID;

  public function __construct($accountID) {
    $this->accountID = $accountID;
  }

  public function transactionTable() {
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

  protected function tableHeaders() {
    return array(
      t('Label'),
      t('Time'),
      t('Amount'),
    );
  }

  protected function tableRows() {
    $transactions = $this->queryTransactions();
    $rows = array();
    if ($transactions !== false) {
      foreach (array_keys($transactions) as $key => $entityID) {
        $transaction = new TransactionLineItem(TransactionLineItem::loadEntity($entityID));
        $rows[] = array(
          $transaction->label(),
          format_date($transaction->getCreated()),
          FinancialsUtils::currencyFormat(FinancialsUtils::priceFieldAmount($transaction->getTotal())),
        );
      }
    }
    return $rows;
  }

  protected function queryTransactions() {
    $query = new \EntityFieldQuery();
    $query->entityCondition('entity_type', TRANSACTION_ENTITY)
      ->entityCondition('bundle', TRANSACTION_ENTITY_BUNDLE)
      ->fieldCondition(TRANSACTION_ACCOUNT_REF_FIELD, 'target_id', $this->accountID)
      ->propertyOrderBy('created', 'DESC');
    $results = $query->execute();
    return reset($results);
  }
}
