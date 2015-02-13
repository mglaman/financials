<?php

namespace Drupal\financials\Forms;

use Drupal\financials\Entity\BudgetLineItem;

class BudgetForm {
  protected $lineItemType;
  protected $isNew;

  public function __construct() {
    $this->lineItemType = BudgetLineItem::loadBundle();
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

    // Add a default save button.
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->isNew ? t('Add budget item') : t('Update budget item'),
      '#submit' => array(
        array('\Drupal\\financials\\Forms\\BudgetForm', 'submit')
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

    $handler = new BudgetLineItem($lineItem);

    // Set the label
    if (!$lineItem->line_item_label) {
      $handler->setLabel();
    }
    $handler->save();

    drupal_set_message(
      t('Budget item has been recorded')
    );
  }
}
