<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the trombino point service entity type.
 */
final class TrombinoPointServiceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Point Service');
    $header['type'] = $this->t('Type');
    $header['region'] = $this->t('Region');
    $header['status'] = $this->t('Status');
    $header['created'] = $this->t('Created');
    $header['changed'] = $this->t('Updated');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\orange_yelen_trombino\TrombinoPointServiceInterface $entity */
    $row['id'] = $entity->id();
    $row['name'] = $entity->get('name')->value;
    $row['type']['data'] = $entity->get('type')->view(['label' => 'hidden']);
    $row['region']['data'] = $entity->get('regions')->view(['label' => 'hidden']);
    $row['status'] = $entity->get('status')->value ? $this->t('Published') : $this->t('Pending');
    $username_options = [
      'label' => 'hidden',
      'settings' => ['link' => $entity->get('uid')->entity->isAuthenticated()],
    ];
    $row['uid']['data'] = $entity->get('uid')->view($username_options);
    $row['created']['data'] = $entity->get('created')->view(['label' => 'hidden']);
    $row['changed']['data'] = $entity->get('changed')->view(['label' => 'hidden']);
    return $row + parent::buildRow($entity);
  }

}
