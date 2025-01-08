<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a Orange Yelen Chat form.
 */
final class CloseConversationForm extends ConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'orange_yelen_chat_close_conversation';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $conversation_id = \Drupal::routeMatch()->getParameter('conversation');
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Clôturer la conversation'),

      ],
    ];

    $form['conversation'] = [
      '#type' => 'hidden',
      '#value' => $conversation_id,
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
    $conversationId = $form_state->getValue('conversation');
    $conversation = \Drupal::entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('update')) {
      // return new JsonResponse(['error' => 'Access denied'], 403);
      $this->messenger()->addError($this->t('Access denied'));
      return;
    }
    $conversation->set('status', 'closed');
    $conversation->setChangedTime(time())->save();

    $this->messenger()->addStatus($this->t('Conversation clôturée avec succès.'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url(\Drupal::routeMatch()->getRouteName());
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Voulez-vous vraiment clôturer cette conversation ?');
  }

}
