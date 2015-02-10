<?php

namespace Drupal\financials\Displays;

use Drupal\financials\FinancialsUtils;
use Drupal\financials\Entity\BudgetLineItem;

/**
 * Class AccountsOverview
 * @package Drupal\financials\Pages
 */
class BudgetOverview {
  protected $netIncome = 0;
  protected $netExpense = 0;
  protected $netDiff = 0;

  public function allBudgetItems() {
    return array(
      '#theme' => 'table',
      '#header' => $this->tableHeaders(),
      '#rows' => $this->tableRows(),
      '#empty' => t('No budget items added yet.'),
      '#sticky' => true,
      '#attributes' => array(
        'class' => array('financials-budget-overview')
      ),
    );
  }

  protected function queryLineItems() {
    $query = new \EntityFieldQuery();
    $query->entityCondition('entity_type', 'commerce_line_item')
      ->propertyCondition('type', BUDGET_ENTITY_BUNDLE);
    $result = $query->execute();

    if (isset($result['commerce_line_item'])) {
      return array_keys($result['commerce_line_item']);
    }
    else {
      return array();
    }
  }

  protected function tableHeaders() {
    return array(
      t('Budget Item'),
      t('Account'),
      t('Amount'),
    );
  }

  protected function tableRows() {
    $rows = array();
    foreach ($this->queryLineItems() as $entityID) {
      $budgetItem = new BudgetLineItem(BudgetLineItem::loadEntity($entityID));
      $budgetType = $budgetItem->getType();
      $budgetAccount = $budgetItem->getAccount();
      $budgetAmount = $budgetItem->getTotal();

      if ($budgetType == BudgetLineItem::BUDGET_INCOME) {
        $this->netIncome += $budgetAmount;
      }
      else {
        $this->netExpense += $budgetAmount;
      }

      $rows[] = array(
        'data' => array(
          l($budgetAccount->label(), $budgetAccount->getPath()),
          $budgetItem->label(),
          FinancialsUtils::currencyFormat($budgetAmount),
        ),
        'class' => array(
          'financials-overview-budget-row',
          ($budgetType == BudgetLineItem::BUDGET_INCOME) ? 'ok' : 'error',
        )
      );
    }
    $this->netDiff = ($this->netIncome - $this->netExpense);
    $rows = $this->appendStats($rows);

    return $rows;
  }

  protected function appendStats($rows) {
    if (count($rows)) {
      $rows[] = array(
        'data' => array(
          '',
          '',
          t('Balance: @balance', array('@balance' => FinancialsUtils::currencyFormat($this->netDiff))),
        ),
        'class' => array(
          'info'
        )
      );
    }

    return $rows;
  }
}