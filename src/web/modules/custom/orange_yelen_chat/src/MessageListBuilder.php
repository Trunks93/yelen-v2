<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the message chat entity type.
 */
final class MessageListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['status'] = $this->t('Status');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\orange_yelen_chat\MessageInterface $entity */
    $row['id'] = $entity->id();
    $row['status'] = $entity->get('status')->value ? $this->t('Enabled') : $this->t('Disabled');
    $row['created']['data'] = $entity->get('created')->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
