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
final class UtilityContentForm extends FormBase
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

    $form['message']['#markup'] = "<h3>Ce contenu vous a t'il été utile ? </h3>";


    $form['content'] = [
      '#type' => 'hidden',
      '#value' => $nodeId
    ];

    $form['actions'] = [
      '#type' => 'actions',
      '#prefix' => '<div class="mb-3 d-flex justify-content-center">',
      '#suffix' => '</div>',
    ];
    $form['actions']['inutile'] = [
      '#type' => 'submit',
      '#value' => $this->t('Non'),
      '#attributes' => ['class' => ['btn', 'btn-outline-secondary', 'me-2', 'no']],
      '#theme' => 'utility_button'
    ];
    $form['actions']['utile'] = [
      '#type' => 'submit',
      '#value' => $this->t('Oui'),
      '#attributes' => ['class' => ['btn', 'btn-primary', 'yes']],
      '#theme' => 'utility_button'
    ];

    $form['#attached']['library'][] = 'yelen_content_evaluation/content_evaluation';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void
  {
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
    $triggering_element = $form_state->getTriggeringElement();
    $button_clicked = $triggering_element['#id'];
    switch ($button_clicked){
      case 'edit-utile':
        $utility = "oui";
        break;
      case 'edit-inutile':
        $utility = "non";
        break;
        default:
          $utility = "non";
        break;
    }
    $evaluation =\Drupal::service('evaluation.service')->getEvaluationOfUser($userid,$nodeId);
   // dd($nodeId,$userid,$title,$utility,$evaluation);
    if(empty($evaluation)){
      $utilite = Evaluation::create(['bundle'=>'simple','label'=>'#'.$title,
        'field_contenu'=>$content,'utility'=>$utility]);
      $utilite->save();
    }else{
      $utilite = current($evaluation);
      $utilite->set('utility',$utility);
      $utilite->save();
    }
    $this->messenger()->addStatus($this->t("Merci pour votre participation"));
    $form_state->setRedirect('entity.node.canonical',['node'=>$nodeId]);
  }

}