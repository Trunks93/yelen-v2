<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Orange Yelen Search form.
 */
final class SearchRatingForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'orange_yelen_search_search_rating';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['rating'] = [
      '#type' => 'radios',
      '#title' => $this->t('Votre note'),
      '#options' => [
        5 => '5',
        4 => '4',
        3 => '3',
        2 => '2',
        1 => '1',
      ],
      '#theme' => 'rating_star',
      '#prefix' => '<div class="rating">',
      '#suffix' => '</div>',
      '#default_value' => '1',
      '#required' => TRUE,
    ];

    // Section pour le commentaire
    $form['comment'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Votre commentaire'),
      '#attributes' => ['class' => ['form-control']],
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      '#prefix' => '<div class="mb-3">',
      '#suffix' => '</div>',
    ];
    $form['actions']['close'] = [
      '#type' => 'button',
      '#value' => $this->t('Fermer'),
      '#attributes' => ['class' => ['btn', 'btn-outline-secondary'], 'data-bs-dismiss' => 'modal'],
      '#theme' => 'boosted_button'
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soumettre'),
      '#attributes' => ['class' => ['btn', 'btn-primary']],
      '#theme' => 'boosted_button'
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $rating = $form_state->getValue('rating');
    $comment = $form_state->getValue('comment');
    $current_user = \Drupal::currentUser();
    $current_user_name = $current_user->getAccountName();
    $search_term = \Drupal::request()->query->get('search_api_fulltext');
    $notificationService = \Drupal::service('yelen_notification.sendmail');

    $subject = 'Evaluation de Recherche';
    $receiver = 'saintcyrwin@gmail.com, julius.konan@synelia.tech';
    $cc = null;
    $notificationTemplate = [
        '#theme' => 'rating_notification',
        '#content' => [
            'username' => $current_user_name,
            'search_term' => $search_term,
            'comment' => $comment,
            'rating' => $rating,
        ],
    ];

    try {
        $notificationService->sendNotification($subject, $receiver, $cc, $notificationTemplate);
        $this->messenger()->addStatus($this->t('Votre évaluation a bien été prise en compte. Merci !'));
    } catch (\Exception $e){
        $this->messenger()->addError($this->t('Désolé,  nous ne sommes pas parvenus à enregistrer votre évaluation'));
    }

    // Enregistrement de l'évaluation
    /* $node = \Drupal\node\Entity\Node::create([
      'type' => 'evaluation_recherche',
      'title' => $this->t('Évaluation du %date', ['%date' => date('Y-m-d H:i:s')]),
      'field_rating' => $rating,
      'field_comment' => $comment,
    ]);
    $node->save(); */

    // $form_state->setRedirect('<front>');
  }

}
