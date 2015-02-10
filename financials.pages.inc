<?php

use Drupal\financials\Forms\TransactionForm;
use Drupal\financials\Forms\BudgetForm;
use Drupal\financials\Forms\AccountTransferForm;

/**
 * @file
 * Page callbacks when we can't invoke OOP
 */

function financials_add_transaction_form($form, &$form_state, $line_item) {
  $formBuilder = new TransactionForm();
  return $formBuilder->getForm($form, $form_state, $line_item);
}

function financials_add_transaction_form_submit($form, &$form_state) {
  TransactionForm::submit($form, $form_state);
}

function financials_transfer_transaction_form($form, &$form_state) {
  $formBuilder = new AccountTransferForm();
  return $formBuilder->getForm($form, $form_state);
}

function financials_add_budget_form($form, &$form_state, $line_item) {
  $formBuilder = new BudgetForm();
  return $formBuilder->getForm($form, $form_state, $line_item);
}