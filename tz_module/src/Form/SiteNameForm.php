<?php

namespace Drupal\tz_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides ability to change site name
 */
class SiteNameForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_name_change_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['site_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site name'),
      '#default_value' => $this->configFactory()->getEditable('system.site')->get('name'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('site_name');
    if (strlen($name) < 6) {
      $form_state->setError($form['site_name'], $this->t('Site name should consist more than 6 symbols'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $site_name = $form_state->getValue('site_name');
    if ($site_name) {
      $this->configFactory()->getEditable('system.site')->set('name', $site_name)->save();
    }
  }

}
