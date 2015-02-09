<?php

use Drupal\financials\Entity\TransactionLineItem;

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'single' => TRUE,
  'title' => t('Add transfer form'),
  'icon' => '../icon_form.png',
  'description' => t('Adds a general transfer form'),
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
function financials_ctools_account_transfer_form_content_type_render($subtype, $conf, $panel_args, &$context) {
  $block = (object) array(
    'subject' => t('Account transfer'),
    'content' => drupal_get_form('financials_transfer_transaction_form'),
    'delta' => 'financials_account_transfer_form'
  );
  return $block;
}

function financials_ctools_account_transfer_form_content_type_admin_title($subtype, $conf, $context) {
  return t('"@s" base form', array('@s' => $context->identifier));
}

function financials_ctools_account_transfer_form_content_type_edit_form($form, &$form_state) {
  // provide a blank form so we have a place to override title
  // and stuff.
  return $form;
}
