<?php

use Drupal\financials\Entity\TransactionLineItem;

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'single' => TRUE,
  'title' => t('Add transaction form'),
  'icon' => '../icon_form.png',
  'description' => t('Adds a general transaction form'),
  'category' => t('Financials'),
);

/**
 * Renders the form.
 *
 * @param $subtype
 * @param $conf
 * @param $panel_args
 * @param $context
 * @return \stdClass
 */
function financials_ctools_add_transaction_form_content_type_render($subtype, $conf, $panel_args, &$context) {
  $block = (object) array(
    'subject' => t('Add transaction'),
    'content' => drupal_get_form('financials_add_transaction_form', TransactionLineItem::newStub()),
    'delta' => 'financials_add_transaction_form'
  );
  return $block;
}

function financials_ctools_add_transaction_form_content_type_admin_title($subtype, $conf, $context) {
  return t('"@s" base form', array('@s' => $context->identifier));
}

function financials_ctools_add_transaction_form_content_type_edit_form($form, &$form_state) {
  // provide a blank form so we have a place to override title
  // and stuff.
  return $form;
}
