<?php

namespace Drupal\financials\Entity;

use Drupal\financials\FinancialsUtils;

class TransactionLineItem extends FinancialsEntityBase implements FinancialsEntityHelperInterface {

  protected $entityType = TRANSACTION_ENTITY;

  public function save() {
    // On save, we need to check if it is a new transaction and update account
    if (isset($this->entity->is_new)) {

      $transactionAmount = FinancialsUtils::priceFieldAmount($this->getTotal());
      $account = new AccountNode($this->getAccount()->getIdentifier());
      $account->adjustCurrentBalance($transactionAmount);

    }
    parent::save();
  }

  /**
   * Returns wrapper for account node reference.
   *
   * @return \EntityDrupalWrapper
   * @throws \EntityMetadataWrapperException
   */
  public function getAccount() {
    return $this->wrapper->get(TRANSACTION_ACCOUNT_REF_FIELD);
  }

  /**
   * @return \EntityStructureWrapper
   * @throws \EntityMetadataWrapperException
   */
  public function getTotal() {
    return $this->wrapper->get('commerce_unit_price');
  }

  /**
   * Returns line item entity stub.
   * @return \stdClass
   */
  static function newStub() {
    return commerce_line_item_new(TRANSACTION_ENTITY_BUNDLE);
  }

  /**
   * @return \stdClass|bool
   */
  static function loadBundle() {
    return commerce_line_item_type_load(TRANSACTION_ENTITY_BUNDLE);
  }

  /**
   * @param $entityID
   * @return bool|\stdClass
   */
  static function loadEntity($entityID) {
    return commerce_line_item_load($entityID);
  }
}