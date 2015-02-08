<?php

namespace Drupal\financials;

/**
 * Class FinancialsFieldHelper
 *
 * Provides helper functions to create module's fields.
 */
class FinancialsFieldHelper {
  protected $entityType;
  protected $entityBundle;
  protected $fieldName;
  protected $fieldInfo;
  protected $fieldInstance;

  public function __construct($fieldName, $entityType, $entityBundle) {
    $this->entityType = $entityType;
    $this->entityBundle = $entityBundle;

    field_cache_clear();
    $this->fieldName = $fieldName;
    $this->fieldInfo = field_info_field($fieldName);
    $this->fieldInstance = field_info_instance($entityType, $fieldName, $entityBundle);
  }

  public function fieldExists() {
    return $this->fieldInfo !== null;
  }

  public function instanceExists() {
    return $this->fieldInstance !== null;
  }

  public function createField($fieldType, $cardinality, $settings) {
    $field = array(
      'field_name' => $this->fieldName,
      'type' => $fieldType,
      'cardinality' => $cardinality,
      'entity_types' => array($this->entityType),
      'translatable' => 0,
      'locked' => TRUE,
      'settings' => $settings,
    );
    field_create_field($field);
  }

  public function createInstance($label, $widget, $display) {
    $instance = array(
      'field_name' => $this->fieldName,
      'entity_type' => $this->entityType,
      'bundle' => $this->entityBundle,
      'label' => $label,
      'required' => TRUE,
      'settings' => array(),
      'widget' => $widget,
      'display' => array(
        'display' => $display,
      ),
    );
    field_create_instance($instance);
  }

  public function deleteField() {
    field_delete_field($this->fieldName);
  }

  public function deleteInstance() {
    field_delete_instance($this->fieldInstance);
  }
}