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
        5 => '<label for="star5">&#9733;</label>',
        4 => '<label for="star4">&#9733;</label>',
        3 => '<label for="star3">&#9733;</label>',
        2 => '<label for="star2">&#9733;</label>',
        1 => '<label for="star1">&#9733;</label>',
      ],
      '#prefix' => '<div class="rating">',
      '#suffix' => '</div>',
      '#attributes' => ['class' => ['form-control']],
      '#default_value' => '5',
      '#required' => TRUE,
    ];

    // Section pour le commentaire
    $form['comment'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Votre commentaire'),
      '#attributes' => ['class' => ['form-control']],
      '#required' => TRUE,
    ];

    // Boutons de soumission et de fermeture
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['close'] = [
      '#type' => 'button',
      '#value' => $this->t('Fermer'),
      '#attributes' => ['class' => ['btn', 'btn-outline-secondary'], 'data-bs-dismiss' => 'modal'],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soumettre'),
      '#attributes' => ['class' => ['btn', 'btn-primary']],
    ];

    // $form['#theme'] = 'orange_yelen_search_search_rating_form';

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

    $search_term = \Drupal::request()->query->get('search_api_fulltext');

    dump($rating, $comment, $search_term);
    die('Ok');

    // Enregistrement de l'évaluation
    /* $node = \Drupal\node\Entity\Node::create([
      'type' => 'evaluation_recherche',
      'title' => $this->t('Évaluation du %date', ['%date' => date('Y-m-d H:i:s')]),
      'field_rating' => $rating,
      'field_comment' => $comment,
    ]);
    $node->save(); */
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('<front>');
  }

}
