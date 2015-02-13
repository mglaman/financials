<?php

namespace Drupal\financials\Forms;

use Drupal\financials\Entity\TransactionLineItem;
use Drupal\financials\FinancialsUtils;

class TransactionForm {
  protected $lineItemType;
  protected $isNew;

  public function __construct() {
    $this->lineItemType = TransactionLineItem::loadBundle();
  }

  public function getForm($form, &$form_state, $lineItem) {
    $this->isNew = ($lineItem->line_item_id === null);
    // Add the field related form elements.
    $form_state['commerce_line_item'] = $lineItem;
    field_attach_form('commerce_line_item', $lineItem, $form, $form_state);
    $this->addActions($form);
    return $form;
  }

  public function addActions(&$form) {
    $form['actions'] = array('#type' => 'actions');

    // Store the line item info object in the form array.
    $form['actions']['line_item_type'] = array(
      '#type' => 'value',
      '#value' => $this->lineItemType,
    );

    // Add a default save button.
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->isNew ? t('Add transaction') : t('Update transaction'),
      '#submit' => array(
        array('\Drupal\\financials\\Forms\\TransactionForm', 'submit')
      ),
    );
  }

  static function submit($form, &$form_state) {
    $lineItem = $form_state['commerce_line_item'];

    if (!$lineItem->created) {
      $lineItem->created = REQUEST_TIME;
    }
    // Notify field widgets.
    field_attach_submit('commerce_line_item', $lineItem, $form, $form_state);

    $handler = new TransactionLineItem($lineItem);

    // Set the label
    if (!$lineItem->line_item_label) {
      $accountLabel = $handler->getAccount()->label();
      $transactionAmount = FinancialsUtils::priceFieldAmount($handler->getTotal());

      $lineItem->line_item_label = t('Transaction on @account for @amount', array(
        '@account' => $accountLabel,
        '@amount' => FinancialsUtils::currencyFormat($transactionAmount),
      ));
    }
    $handler->save();

    drupal_set_message(
      t('Transfer has been recorded')
    );
  }
}
