<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the yelen tchat entity type.
 */
final class YelenTchatListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['label'] = $this->t('Label');
    $header['status'] = $this->t('Status');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\orange_yelen_chat\YelenTchatInterface $entity */
    $row['id'] = $entity->id();
    $row['label'] = $entity->toLink();
    $row['status'] = $entity->get('status')->view(['label' => 'hidden']);
    $row['created']['data'] = $entity->get('created')->view(['label' => 'hidden']);
    $row['changed']['data'] = $entity->get('changed')->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
