<?php

namespace Drupal\financials\Entity;

interface FinancialsEntityHelperInterface {

  /**
   * Returns entity stub for new instance creation
   * @return \stdClass
   */
  static function newStub();

  /**
   * Helper function to load entity bundle data
   * @return mixed
   */
  static function loadBundle();

  /**
   * Helper function to load entity.
   * @param $entityId
   * @return \stdClass
   */
  static function loadEntity($entityId);
}