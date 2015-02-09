<?php

use Drupal\financials\Displays\AccountsOverview;

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'single' => TRUE,
  'title' => t('Accounts Overview'),
  'icon' => '../icon_token.png',
  'description' => t('Displays a table of accounts'),
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
function financials_ctools_accounts_overview_content_type_render($subtype, $conf, $panel_args, &$context) {
  $overview = new AccountsOverview();
  $block = (object) array(
    'subject' => t('Accounts'),
    'content' => $overview->allAccounts(),
    'delta' => 'financials_accounts_overview'
  );
  return $block;
}

function financials_ctools_accounts_overview_content_type_admin_title($subtype, $conf, $context) {
  return t('"@s" base form', array('@s' => $context->identifier));
}

function financials_ctools_accounts_overview_content_type_edit_form($form, &$form_state) {
  // provide a blank form so we have a place to override title
  // and stuff.
  return $form;
}
