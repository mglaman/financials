<?php

namespace Drupal\financials_ctools\ModalForms;

class AccountEdit extends ModalForm {
  protected function ctoolsIncludes() {
    parent::ctoolsIncludes();
    ctools_include('node.pages', 'node', '');
  }

  protected function title() {
    return t('Edit');
  }
}