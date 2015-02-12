<?php

namespace Drupal\financials_ctools\ModalForms;

class ModalForm {
  protected $js;
  protected $baseFormId;
  protected $formArgs;
  protected $formState;

  public function __construct($formId, $args, $js = false) {
    $this->js = $js;
    $this->baseFormId = $formId;
    $this->formArgs = $args;
    $this->ctoolsIncludes();
    $this->formStateStub();
  }

  public function output() {
    if (!$this->js) {
      return drupal_get_form($this->baseFormId);
    }
    $output = ctools_modal_form_wrapper($this->baseFormId, $this->formState);
    if ($this->wasExecuted()) {
      $output = $this->executedOutput();
    }

    print ajax_render($output);
    exit;
  }

  protected function executedOutput() {
    $output = array();
    $output[] = ctools_modal_command_dismiss();
    $output[] = ctools_ajax_command_reload();
    return $output;
  }

  protected function ctoolsIncludes() {
    ctools_include('modal');
    ctools_include('ajax');
  }

  protected function title() {
    return $this->baseFormId;
  }

  protected function formStateStub() {
    $this->formState = array(
      'title' => $this->title(),
      'ajax' => true,
    );
    $this->formState['build_info']['args'] = $this->formArgs;
  }

  protected function wasExecuted() {
    return !empty($this->formState['executed']);
  }
}