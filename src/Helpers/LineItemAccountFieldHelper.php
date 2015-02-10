<?php
namespace Drupal\financials\Helpers;

class LineItemAccountFieldHelper extends FinancialsFieldHelper {

  public function __construct($fieldName, $entityType, $entityBundle) {
    parent::__construct($fieldName, $entityType, $entityBundle);
  }

  protected function fieldSettings() {
    return array(
      'handler_settings' => array(
        'target_bundles' => array(
          'finance_account' => 'finance_account',
        ),
      ),
    );
  }

  protected function fieldInstanceSettings() {
    return array(
      'widget' => array(
        'type' => 'options_select',
        'weight' => 0,
      ),
      'display' => array()
    );
  }

  public function createField() {
    if (!$this->fieldExists()) {
      parent::createField('entityreference', 1, $this->fieldSettings());
    }
  }

  public function createInstance($label, $required = true) {
    if (!$this->instanceExists()) {
      $settings = $this->fieldInstanceSettings();
      parent::createInstance($label, $settings['widget'], $settings['display'], $required);
    }
  }
}