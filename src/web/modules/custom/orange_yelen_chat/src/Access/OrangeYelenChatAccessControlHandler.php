<?php

namespace Drupal\orange_yelen_chat\Access;


use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Logger\LoggerChannelTrait;
/**
 * Access controller for the Message and Conversation entity type.
 */
class OrangeYelenChatAccessControlHandler extends EntityAccessControlHandler
{
  use LoggerChannelTrait;
  public function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // $this->getLogger('orange_yelen_chat')->notice('checkAccess called for operation: @operation', ['@operation' => $operation]);
    if ($account->hasPermission('administer site configuration')) {
      return AccessResult::allowed();
    }

    switch ($entity->getEntityTypeId()) {
      case 'orange_yelen_chat_conversation':
        return $this->checkConversationAccess($entity, $operation, $account);

      case 'orange_yelen_chat_message':
        return $this->checkMessageAccess($entity, $operation, $account);
    }

    return AccessResult::neutral();
  }

  protected function checkConversationAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $is_participant = $entity->getCreatedBy()->id() === $account->id() || $entity->getParticipant()->id() === $account->id();
    if (!$is_participant) {
      return AccessResult::forbidden();
    }

    if ($operation === 'update' && $entity->getStatus() === 'closed') {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }

  protected function checkMessageAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $conversation = $entity->getConversation();

    if (!$conversation) {
      return AccessResult::forbidden();
    }

    $is_participant = $conversation->getCreatedBy()->id() === $account->id() ||
      $conversation->getParticipant()->id() === $account->id();

    if (!$is_participant) {
      return AccessResult::forbidden();
    }

    if ($operation === 'update' && $conversation->getStatus() === 'closed') {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }
}
