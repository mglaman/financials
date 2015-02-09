<?php

namespace Drupal\financials\Entity;

class FinancialsEntityBase implements FinancialsEntityInterface {
  /**
   * @var \stdClass
   */
  protected $entity;

  /**
   * @var string
   */
  protected $entityType;

  /**
   * @var \EntityDrupalWrapper
   */
  protected $wrapper;

  public function __construct($entity) {
    $this->entity = $entity;
    $this->wrapper = new \EntityDrupalWrapper($this->entityType, $entity);
  }

  public function label() {
    return $this->wrapper->label();
  }

  public function save() {
    $this->wrapper->save();
  }

}