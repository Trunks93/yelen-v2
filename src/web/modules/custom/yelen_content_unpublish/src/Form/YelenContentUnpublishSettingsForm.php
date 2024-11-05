<?php

namespace Drupal\yelen_content_unpublish\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;

class YelenContentUnpublishSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames() {
    return ['yelen_content_unpublish.settings'];
  }

  public function getFormId() {
    return 'yelen_content_unpublish_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('yelen_content_unpublish.settings');

    $default_value = $config->get('default_unpublish_date');
    if (!$default_value) {
      $default_days = 0;

    }

    $form['default_unpublish_date'] = [   
      '#type' => 'number',
      '#title' => $this->t(string: 'Date de dépublication '),
      '#default_value' => $default_days,
      '#description' => $this->t(string: 'Entrez le nombre de jours pour la dépublication '),
      '#min' => 0,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $value = $form_state->getValue('default_unpublish_date');
    if ($value instanceof DrupalDateTime) {
      $value = $value->format('Y-m-d\TH:i:s');
    }

    $this->config('yelen_content_unpublish.settings')
      ->set('default_unpublish_date', $value)
      ->save();

    // // Ajoutez ce message de débogage
     \Drupal::messenger()->addMessage('Date saved: ' . $value);

    parent::submitForm($form, $form_state);
  }
}