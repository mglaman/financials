<?php
namespace Drupal\financials\Helpers;

class LineItemCategoryFieldHelper extends FinancialsFieldHelper {

  public function __construct($fieldName, $entityType, $entityBundle) {
    parent::__construct($fieldName, $entityType, $entityBundle);
  }

  protected function fieldSettings() {
    return array(
      'allowed_values' => array(
        array(
          'vocabulary' => 'financials_transaction_categories',
          'parent' => 0,
        ),
      ),
    );
  }

  protected function fieldInstanceSettings() {
    return array(
      'widget' => array(
        'type' => 'options_select',
      ),
      'display' => array(
        'label' => 'hidden',
        'weight' => 0,
        'type' => 'taxonomy_term_reference_plain'
      )
    );
  }

  public function createField() {
    if (!$this->fieldExists()) {
      parent::createField('taxonomy_term_reference', 1, $this->fieldSettings());
    }
  }

  public function createInstance($label, $required = true) {
    if (!$this->instanceExists()) {
      $settings = $this->fieldInstanceSettings();
      parent::createInstance($label, $settings['widget'], $settings['display'], $required);
    }
  }
}