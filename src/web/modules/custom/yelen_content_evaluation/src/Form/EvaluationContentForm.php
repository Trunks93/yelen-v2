<?php

declare(strict_types=1);

namespace Drupal\yelen_content_evaluation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\yelen_content_evaluation\Entity\Evaluation;

/**
 * Provides a yelen content evaluation form.
 */
final class EvaluationContentForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string
  {
    return 'yelen_content_evaluation_utility_content';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $nodeId = NULL): array
  {

    $form['evaluation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Evaluez ce contenu'),
      '#options' => [
        5 => '5',
        4 => '4',
        3 => '3',
        2 => '2',
        1 => '1',
      ],
      '#theme' => 'evaluation_star',
      '#prefix' => '<div class="evaluation">',
      '#suffix' => '</div>',
      '#default_value' => '1',
      '#required' => TRUE,

    ];

    // Section pour le commentaire
    $form['comment'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Votre commentaire'),
      '#attributes' => ['class' => ['form-control']],
      '#required' => FALSE,
    ];
    $form['content'] = [
      '#type' => 'hidden',
      '#value' => $nodeId
    ];

    $form['actions'] = [
      '#type' => 'actions',
      '#prefix' => '<div class="mb-3">',
      '#suffix' => '</div>',
    ];
    $form['actions']['close'] = [
      '#type' => 'button',
      '#value' => $this->t('Fermer'),
      '#attributes' => ['class' => ['btn', 'btn-outline-secondary', 'me-2', 'fermer'], 'data-bs-dismiss' => 'modal'],
      '#theme' => 'boosted_button'
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Soumettre'),
      '#attributes' => ['class' => ['btn', 'btn-primary', 'soumettre']],
      '#theme' => 'boosted_button'
    ];
    $form['#attached']['library'][] = 'yelen_content_evaluation/content_evaluation';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void
  {
    //dd($nodeId);
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
  public function submitForm(array &$form, FormStateInterface $form_state): void
  {
    $nodeId = $form_state->getValue('content');
    $userid =\Drupal::currentUser()->id();
    $content = Node::load($nodeId);
    $title = sprintf('%s_%s',$content->uuid(),$userid);
    $note = $form_state->getValue('evaluation');
    $description = $form_state->getValue('comment');

    $evaluation =\Drupal::service('evaluation.service')->getEvaluationOfUser($userid,$nodeId);
    if(empty($evaluation)){
      $eval = Evaluation::create(['bundle'=>'simple','label'=>'#'.$title,
        'evaluation'=>$note,
        'field_contenu'=>$content,
        'description'=>$description]);
      $eval->save();
    }else{
      $eval = current($evaluation);
      $eval->set('evaluation',$note);
      $eval->set('description',$description);
      $eval->save();
    }


    $this->messenger()->addStatus($this->t("Merci pour votre participation"));
    $form_state->setRedirect('faq-page');
  }

}
