<?php

namespace Drupal\financials\Entity;

use Drupal\financials\FinancialsUtils;

class AccountNode extends FinancialsEntityBase implements FinancialsEntityHelperInterface {

  const DEBT_ACCOUNT = 0;
  const ASSET_ACCOUNT = 1;

  protected $entityType = 'node';

  public function getPath() {
    return 'node/' . $this->wrapper->getIdentifier();
  }

  protected function getFieldBalance($fieldName) {
    /** @var \EntityStructureWrapper $fieldWrapper */
    $fieldWrapper = $this->wrapper->get($fieldName);
    $balance = FinancialsUtils::priceFieldAmount($fieldWrapper);

    if ($this->getAccountType() == self::DEBT_ACCOUNT) {
      $balance = $balance * -1;
    }
    return $balance;
  }

  /**
   * @return mixed
   * @throws \EntityMetadataWrapperException
   */
  public function getStartingBalance() {
    return $this->getFieldBalance(ACCOUNT_STARTING_BALANCE_FIELD);
  }

  /**
   * @return mixed
   * @throws \EntityMetadataWrapperException
   */
  public function getCurrentBalance() {
    return $this->getFieldBalance(ACCOUNT_CURRENT_BALANCE_FIELD);
  }

  public function setCurrentBalance($amount) {
    $this->wrapper->get(ACCOUNT_CURRENT_BALANCE_FIELD)->get('amount')->set($amount);
    $this->save();
  }

  public function adjustCurrentBalance($amount) {
    if ($this->getAccountType() == self::DEBT_ACCOUNT) {
      $amount = $amount * -1;
      $newAccountBalance = ($this->getCurrentBalance() + $amount) * -1;
    }
    else {
      $newAccountBalance = ($this->getCurrentBalance() + $amount);
    }
    $this->setCurrentBalance($newAccountBalance);
  }

  /**
   * @return boolean
   * @throws \EntityMetadataWrapperException
   */
  public function getAccountType() {
    return $this->wrapper->get(ACCOUNT_BALANCE_TYPE_FIELD)->value();
  }


  static function newStub() {
    $node = (object) array(
      'uid' => $GLOBALS['user']->uid,
      'name' => (isset($GLOBALS['user']->name) ? $GLOBALS['user']->name : ''),
      'type' => 'finance_account',
      'language' => LANGUAGE_NONE
    );
    node_object_prepare($node);

    return $node;
  }

  static function loadBundle() {
    $types = node_type_get_types();
    return $types['finance_account'];
  }

  static function loadEntity($entityID) {
    return node_load($entityID);
  }

}
