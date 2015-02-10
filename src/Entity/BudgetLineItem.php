<?php

namespace Drupal\financials\Entity;

use Drupal\financials\FinancialsUtils;
use Drupal\financials\Entity\AccountNode;

class BudgetLineItem extends FinancialsEntityBase implements FinancialsEntityHelperInterface {

  const BUDGET_EXPENSE = 0;
  const BUDGET_INCOME = 1;
  protected $entityType = BUDGET_ENTITY;

  /**
   * Returns wrapper for account node reference.
   *
   * @return AccountNode
   * @throws \EntityMetadataWrapperException
   */
  public function getAccount() {
    $value = $this->wrapper->get(TRANSACTION_ACCOUNT_REF_FIELD)->raw();
    return new AccountNode(AccountNode::loadEntity($value));
  }

  public function getTotal() {
    /** @var \EntityStructureWrapper $fieldWrapper */
    $fieldWrapper = $this->wrapper->get('commerce_unit_price');
    $total = FinancialsUtils::priceFieldAmount($fieldWrapper);

    if ($this->getType() == self::BUDGET_EXPENSE) {
      $total = $total * -1;
    }
    return $total;
  }

  public function setLabel($label = null) {
    if (!$label) {
      $this->wrapper->get('line_item_label')->set($this->defaultLabel());
    }
    else {
      $this->wrapper->get('line_item_label')->set($label);
    }
  }

  public function label() {
    return $this->wrapper->get('line_item_label')->value();
  }

  public function defaultLabel() {
    return t('Budget item on @account for @amount', array(
      '@account' => $this->wrapper->get(TRANSACTION_ACCOUNT_REF_FIELD)->label(),
      '@amount' => FinancialsUtils::currencyFormat($this->getTotal()),
    ));
  }

  public function getCreated() {
    return $this->wrapper->get('created')->value();
  }

  /**
   * @return boolean
   * @throws \EntityMetadataWrapperException
   */
  public function getType() {
    return $this->wrapper->get(BUDGET_TYPE_FIELD)->value();
  }

  /**
   * @return \EntityDrupalWrapper
   * @throws \EntityMetadataWrapperException
   */
  public function getCategory() {
    return $this->wrapper->get(BUDGET_CATEGORY_FIELD);
  }

  /**
   * Returns line item entity stub.
   * @return \stdClass
   */
  static function newStub() {
    return commerce_line_item_new(BUDGET_ENTITY_BUNDLE);
  }

  /**
   * @return \stdClass|bool
   */
  static function loadBundle() {
    return commerce_line_item_type_load(BUDGET_ENTITY_BUNDLE);
  }

  /**
   * @param $entityID
   * @return bool|\stdClass
   */
  static function loadEntity($entityID) {
    return commerce_line_item_load($entityID);
  }
}
