<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for a trombino point service entity type.
 */
final class TrombinoPointServiceSettingsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'trombino_point_service_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['orange_yelen_trombino.trombino_point_service_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $config = $this->config('orange_yelen_trombino.trombino_point_service_settings');

    $form['settings'] = [
      '#markup' => $this->t('Configurer Trombino Point Service'),
    ];

    $form['background_image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Background Image'),
      '#upload_location' => 'public://trombino-point-service/',
      '#default_value' => $config->get('background_image'),
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Enregistrer'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('La configuration a été mis à jour avec succès.'));
  }

}
