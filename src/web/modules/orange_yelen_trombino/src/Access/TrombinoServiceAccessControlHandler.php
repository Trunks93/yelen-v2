<?php
namespace Drupal\orange_yelen_trombino\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Access controller for the Trombino Service entity type.
 */
class TrombinoServiceAccessControlHandler extends EntityAccessControlHandler{
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account,
          'view trombino_point_service');
      case 'update':
        return AccessResult::allowedIfHasPermission($account,
          'edit trombino_point_service');
      case 'delete':
        return AccessResult::allowedIfHasPermission($account,
          'delete trombino_point_service');
    }

    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add trombino_point_service');
  }
}
