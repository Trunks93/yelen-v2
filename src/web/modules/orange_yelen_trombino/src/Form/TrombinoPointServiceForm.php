<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the point service entity edit forms.
 */
final class TrombinoPointServiceForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);
    $this->logger('orange_yelen_trombino')->info('Enregistrement d\'un point service.', [$this->entity, $form_state]);

    $message_args = ['%label' => $this->entity->toLink()->toString()];
    $logger_args = [
      '%label' => $this->entity->label(),
      'link' => $this->entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('Point service %label ajouté avec succès.', $message_args));
        $this->logger('orange_yelen_trombino')->notice('Point service %label ajouté avec succès.', $logger_args);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('Le point service %label a été mis à jour avec succès.', $message_args));
        $this->logger('orange_yelen_trombino')->notice('Le point service %label a été mis à jour avec succès.', $logger_args);
        break;

      default:
        throw new \LogicException('Désolé, une erreur est survenue.');
    }

    $form_state->setRedirectUrl($this->entity->toUrl());

    return $result;
  }

}
