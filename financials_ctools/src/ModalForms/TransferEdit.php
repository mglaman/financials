<?php

namespace Drupal\financials_ctools\ModalForms;

class TransferEdit extends ModalForm {
  protected function ctoolsIncludes() {
    parent::ctoolsIncludes();
    ctools_include('financials.pages', 'financials', '');
  }

  protected function title() {
    return t('Edit');
  }
}