<?php

use Drupal\financials\Forms\TransactionForm;

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