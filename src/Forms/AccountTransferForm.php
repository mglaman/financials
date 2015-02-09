<?php

namespace Drupal\financials\Forms;

use Drupal\financials\Entity\TransactionLineItem;

class AccountTransferForm {
  protected $lineItemType;

  public function __construct() {
    $this->lineItemType = TransactionLineItem::loadBundle();
  }

  public function getForm($form, &$form_state) {
    // Add the field related form elements.
    $lineItem = TransactionLineItem::newStub();
    field_attach_form('commerce_line_item', $lineItem, $form, $form_state);

    // Change title
    $form[TRANSACTION_ACCOUNT_REF_FIELD][LANGUAGE_NONE]['#title'] = t('Source Account');

    // Duplicate
    $form['financials_destination_account'] = $form[TRANSACTION_ACCOUNT_REF_FIELD];
    $form['financials_destination_account'][LANGUAGE_NONE]['#title'] = t('Destination Account');
    $this->addActions($form);
    return $form;
  }

  public function addActions(&$form) {
    $form['actions'] = array('#type' => 'actions');

    // Add a default save button.
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => !empty($this->lineItemType['add_form_submit_value']) ? $this->lineItemType['add_form_submit_value'] : t('Save'),
      '#submit' => array(
        array('\Drupal\\financials\\Forms\\AccountTransferForm', 'submit')
      ),
    );
  }

  static function submit($form, &$form_state) {
    $sourceTransaction = TransactionLineItem::newStub();
    $destinationTransaction = TransactionLineItem::newStub();

    $sourceTransaction->created = REQUEST_TIME;
    $destinationTransaction->created = REQUEST_TIME;

    // Handle source transaction.
    $sourceFormState = $form_state;
    $sourceFormState['values']['commerce_unit_price'][LANGUAGE_NONE][0]['amount'] = ($sourceFormState['values']['commerce_unit_price'][LANGUAGE_NONE][0]['amount'] * -1);
    field_attach_submit('commerce_line_item', $sourceTransaction, $form, $sourceFormState);
    $sourceHandler = new TransactionLineItem($sourceTransaction);
    $sourceHandler->setLabel();

    // Handle destination transaction.
    $destinationFormState = $form_state;
    $destinationFormState['values'][TRANSACTION_ACCOUNT_REF_FIELD] = $form_state['values']['financials_destination_account'];
    field_attach_submit('commerce_line_item', $destinationTransaction, $form, $destinationFormState);
    $destinationHandler = new TransactionLineItem($destinationTransaction);
    $destinationHandler->setLabel();

    // Saves
    $sourceHandler->save();
    $destinationHandler->save();

    drupal_set_message(
      t('Transaction "@label" has been recorded', array('@label' => $sourceTransaction->line_item_label))
    );
  }


}
