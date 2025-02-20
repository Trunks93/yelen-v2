<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\yelen_notification\Constante\Constante;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Orange Yelen Chat routes.
 */
final class OrangeYelenChatController extends ControllerBase {
  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }


  public function createConversation(Request $request) {
    if(!$this->currentUser()->id()){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }
    $data = json_decode($request->getContent(), TRUE);
    $participantId = $data['participant_id'];

    // Vérifier si une conversation active existe déjà
    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('status', 'active');

    $or_group = $query->orConditionGroup()
      ->condition('created_by', $this->currentUser()->id())
      ->condition('participant', $this->currentUser()->id());

    $query->condition($or_group);

    $result = $query->execute();

    if (!empty($result)) {
      $conversation = $this->entityTypeManager()
        ->getStorage('orange_yelen_chat_conversation')
        ->load(reset($result));

      return new JsonResponse([
        'id' => $conversation->id(),
        'status' => $conversation->getStatus(),
        'created' => $conversation->getCreatedTime(),
        'updated' => $conversation->getChangedTime(),
        'other_user' => [
          'uid' => $participantId,
          'name' => $this->entityTypeManager()->getStorage('user')->load($participantId)->getDisplayName(),
        ],
      ]);
    }

    // Créer une nouvelle conversation
    $conversation = $this->entityTypeManager()->getStorage('orange_yelen_chat_conversation')->create([
      'created_by' => $participantId,
      'participant' => null,
      'status' => 'active',
    ]);

    $conversation->setCreatedTime(time());
    $conversation->setChangedTime(time());

    $conversation->save();

    return new JsonResponse([
      'id' => $conversation->id(),
      'created_by' => [
        'uid' => $conversation->getCreatedBy()->id(),
        'name' => $conversation->getCreatedBy()->getDisplayName(),
      ],
      'status' => $conversation->getStatus(),
      'created' => $conversation->getCreatedTime(),
      'updated' => $conversation->getChangedTime(),
      'other_user' => null
    ]);
  }
  public function getConversations(string $status = 'active') {
    if(!$this->currentUser()->id()){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }
    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('status', $status);

    $or_group = $query->orConditionGroup()
      ->condition('created_by', $this->currentUser()->id())
      ->condition('participant', $this->currentUser()->id());

    $query->condition($or_group)
      ->sort('changed', 'DESC');

    $result = $query->execute();
    $conversations = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->loadMultiple($result);

    $data = [];
    foreach ($conversations as $conversation) {
      $otherUser = $conversation->getCreatedBy()->id() === $this->currentUser()->id()
        ? $conversation->getParticipant()
        : $conversation->getCreatedBy();

      $data[] = [
        'id' => $conversation->id(),
        'created_by' => [
          'uid' => $conversation->getCreatedBy()->id(),
          'name' => $conversation->getCreatedBy()->getDisplayName(),
        ],
        'status' => $conversation->getStatus(),
        'created' => $conversation->getCreatedTime(),
        'updated' => $conversation->getChangedTime(),
        'other_user' => $otherUser ? [
          'uid' => $otherUser->id(),
          'name' => $otherUser->getDisplayName(),
        ] : null,
        'closed_by' => $conversation->getClosedBy() ? $conversation->getClosedBy()->getDisplayName() : NULL
      ];
    }

    return new JsonResponse($data);
  }

  public function getConversationMessages($conversationId) {
    if(!$this->currentUser()->id()){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }
    $conversation = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('view')) {
      return new JsonResponse(['error' => 'Access denied'], 403);
    }

    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_message')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('conversation', $conversationId)
      ->range(0, 50);

    $result = $query->execute();
    $messages = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_message')
      ->loadMultiple($result);

    $data = [];
    foreach ($messages as $message) {
      $data[] = [
        'id' => $message->id(),
        'user_id' => $message->getUser()->id(),
        'username' => $message->getUser()->getDisplayName(),
        'message' => $message->getMessage(),
        'created' => $message->getCreatedTime(),
        'read' => $message->isRead(),
      ];
    }

    return new JsonResponse($data);
  }

  public function sendMessage(Request $request, $conversationId) {
    if(!$this->currentUser()->id()){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }
    $conversation = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('update')) {
      return new JsonResponse(['error' => 'Access denied'], 403);
    }

    $data = json_decode($request->getContent(), TRUE);

    if(!$data || !isset($data['message'])) {
      return new JsonResponse(['error' => 'Veuillez renseigner le contenu du message.'], 400);
    }

    // Vérifier que celui qui répond,
    // quand il est différent de celui qui a crée la conversation, est un ADMIN
    $isCurrentUserAdmin = \Drupal::currentUser()->hasRole('administrator');
    if($this->currentUser()->id() !== $conversation->getCreatedBy()->id() && !$isCurrentUserAdmin) {
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à répondre à ce message.'], 403);
    }

    $message = $this->entityTypeManager()->getStorage('orange_yelen_chat_message')->create([
      'conversation' => $conversation->id(),
      'user' => $this->currentUser()->id(),
      'message' => $data['message'],
    ]);

    $message->save();
    $conversation->setChangedTime(time())->save();

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
    return new JsonResponse(['status' => 'success']);
  }

  public function closeConversation($conversationId) {
    if(!$this->currentUser()->id()){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }
    $conversation = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('update')) {
      return new JsonResponse(['error' => 'Access denied'], 403);
    }

    //Vérifier que celui qui clôture est participant
    $isParticipant = $this->currentUser()->id() === $conversation->getCreatedBy()->id() || $this->currentUser()->id() !== $conversation->getParticipant()->id();
    $isCurrentUserAdmin = \Drupal::currentUser()->hasRole('administrator');
    if(!$isParticipant && !$isCurrentUserAdmin ){
      return new JsonResponse(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
    }

    $conversation->set('status', 'closed');
    $conversation->set('closed_by', $this->currentUser()->id());
    $conversation->setChangedTime(time());
    $conversation->save();

    return new JsonResponse(['status' => 'success']);
  }


  public function markMessagesAsRead($conversationId) {
    $conversation = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_conversation')
      ->load($conversationId);

    if (!$conversation || !$conversation->access('view')) {
      return new JsonResponse(['error' => 'Access denied'], 403);
    }

    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_message')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('conversation', $conversationId)
      ->condition('user', $this->currentUser()->id(), '<>')
      ->condition('read', 0);

    $result = $query->execute();
    if (!empty($result)) {
      $messages = $this->entityTypeManager()
        ->getStorage('orange_yelen_chat_message')
        ->loadMultiple($result);

      foreach ($messages as $message) {
        $message->set('read', TRUE);
        $message->save();
      }
    }

    return new JsonResponse(['status' => 'success']);
  }

  public function updateOnlineStatus(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    $status = $data['status'] ?? 'online';
    $currentTime = \Drupal::time()->getCurrentTime();

    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_online_status')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('uid', $this->currentUser()->id());
    $result = $query->execute();

    if (!empty($result)) {
      $onlineStatus = $this->entityTypeManager()
        ->getStorage('orange_yelen_chat_online_status')
        ->load(reset($result));
    } else {
      $onlineStatus = $this->entityTypeManager()
        ->getStorage('orange_yelen_chat_online_status')
        ->create(['uid' => $this->currentUser()->id()]);
    }

    $onlineStatus->set('status', $status);
    $onlineStatus->set('last_active', $currentTime);
    $onlineStatus->save();

    // Nettoyer les statuts expirés (plus de 2 minutes d'inactivité)
    $expiredTime = $currentTime - 120;
    $query = $this->entityTypeManager()
      ->getStorage('orange_yelen_chat_online_status')
      ->getQuery()
      ->accessCheck(TRUE)
      ->condition('last_active', $expiredTime, '<');
    $expired = $query->execute();

    if (!empty($expired)) {
      $storage = $this->entityTypeManager()->getStorage('orange_yelen_chat_online_status');
      $entities = $storage->loadMultiple($expired);
      foreach ($entities as $entity) {
        $entity->set('status', 'offline');
        $entity->save();
      }
    }

    return new JsonResponse(['status' => 'success']);
  }

}
