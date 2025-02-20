<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yelen_notification\Constante\Constante;

/**
 * Provides a Orange Yelen Chat form.
 */
final class ReplyMessageForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'orange_yelen_chat_reply_message';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $conversation_id = \Drupal::routeMatch()->getParameter('conversation');

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Répondre au message'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Répondre'),
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

    if (mb_strlen($form_state->getValue('message')) < 2) {
      $form_state->setErrorByName(
        'message',
        $this->t('Le message doit contenir au moins 2 caractères.'),
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $conversationId = $form_state->getValue('conversation');
    $message_content = $form_state->getValue('message');

    $conversation = \Drupal::entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('update')) {
      // return new JsonResponse(['error' => 'Access denied'], 403);
      $this->messenger()->addError($this->t('Access denied'));
      return;
    }

    $message = \Drupal::entityTypeManager()->getStorage('orange_yelen_chat_message')->create([
      'conversation' => $conversation->id(),
      'user' => $this->currentUser()->id(),
      'message' => $message_content
    ]);

    $isCurrentUserAdmin = \Drupal::currentUser()->hasRole('administrator');
    $notificationService = \Drupal::service('yelen_notification.sendmail');

    $subject = 'YELEN - Vous avez reçu un nouveau message';
    $emailExtractor = \Drupal::service('yelen_notification.extract.mail');
    $emails = $isCurrentUserAdmin ? $conversation->getCreatedBy()->getEmail() : $emailExtractor->getEmailsFromBroadcastList(Constante::ALL_YELEN_ADMINS);
    $recipientName = $isCurrentUserAdmin ? $conversation->getCreatedBy()->getAccountName() : 'cher Admin';
    $currentHour = (int) date('H');
    $greeting = $currentHour > 0 && $currentHour <= 12 ? 'Bonjour' : 'Bonsoir';
    $cc = null;
    $notificationTemplate = [
      '#theme' => 'new_message_notification',
      '#content' => [
        'recipient_name' => $recipientName,
        'greeting' => $greeting
      ]
    ];

    try {
      $notificationService->sendNotification($subject, $emails, $cc, $notificationTemplate);
      \Drupal::logger('yelen_chat')->info('Notification de Tchat envoyé avec succès à: %recipient', ['%recipient' => $emails]);
    } catch (\Exception $e){
      \Drupal::logger('yelen_chat')->error('Erreur lors de l\'envoi de notification de Tchat à: %recipient - Erreur: %error', ['%recipient' => $emails, '%error' => $e->getMessage()]);
    }

    $message->save();
    $conversation->set('participant', $this->currentUser()->id());
    $conversation->setChangedTime(time())->save();

    $this->messenger()->addStatus($this->t('Réponse envoyé avec succès.'));
    // $form_state->setRedirect('<front>');
  }

}
