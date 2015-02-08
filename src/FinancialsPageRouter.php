<?php

namespace Drupal\financials;

class FinancialsPageRouter {
  static function loadPage() {
    $args = func_get_args();
    $className = array_shift($args);
    $method = array_shift($args);

    if (class_exists($className)) {
      $class = new $className();
      return call_user_func_array(array($class, $method), $args);
    }
    throw new \Exception('Invalid page class');
  }
}