<?php

namespace Drupal\general\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class GeneralConfigForm.
 */
class GeneralConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'general_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'general_config.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('general_config.settings');

    // Page title field.
    $form['config_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Config title:'),
      '#default_value' => $config->get('general_config.config_title'),
      '#description' => $this->t('Give your Config title.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('general_config.settings');
    $config->set('general_config.config_title', $form_state->getValue('config_title'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

}
