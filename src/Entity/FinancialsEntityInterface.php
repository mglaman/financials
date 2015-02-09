<?php

namespace Drupal\financials\Entity;

interface FinancialsEntityInterface {

  /**
   * Returns entity label
   *
   * Wrapper function for \EntityDrupalWrapper method.
   *
   * @return mixed
   */
  public function label();

  /**
   * Saves the entity
   */
  public function save();
}