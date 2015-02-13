<?php

namespace Drupal\financials\Entity;

use Drupal\financials\FinancialsUtils;

class TransactionLineItem extends FinancialsEntityBase implements FinancialsEntityHelperInterface {

  protected $entityType = TRANSACTION_ENTITY;

  public function save() {
    // On save, we need to update account
    $account = new AccountNode($this->getAccount()->getIdentifier());
    $transactionAmount = FinancialsUtils::priceFieldAmount($this->getTotal());

    // If it's just a new transaction we can update account balance.
    if (isset($this->entity->is_new)) {
      $account->adjustCurrentBalance($transactionAmount);
    }
    // When editing a transaction, we need to see if the previous amount must
    // be rolled back and the new balance adjusted.
    else {
      // @note: We can't load an instance of ourself here, or else it's recurse
      //        into purgatory.
      $original = entity_load_unchanged(TRANSACTION_ENTITY, $this->wrapper->getIdentifier());
      $originalWrapper = new \EntityDrupalWrapper(TRANSACTION_ENTITY, $original);
      $originalAmount = FinancialsUtils::priceFieldAmount($originalWrapper->get('commerce_unit_price'));
      if ($originalAmount != $transactionAmount) {
        // Reverse previous
        $account->adjustCurrentBalance(($originalAmount * -1));
        // Save new.
        $account->adjustCurrentBalance($transactionAmount);
      }
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
    return t('Transaction on @account for @amount', array(
      '@account' => $this->wrapper->get(TRANSACTION_ACCOUNT_REF_FIELD)->label(),
      '@amount' => FinancialsUtils::currencyFormat(FinancialsUtils::priceFieldAmount($this->getTotal())),
    ));
  }

  public function getCreated() {
    return $this->wrapper->get('created')->value();
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
