<?php

declare(strict_types=1);

namespace Drupal\yelen_notification;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of liste diffusion type entities.
 *
 * @see \Drupal\yelen_notification\Entity\BroadcastListType
 */
final class BroadcastListTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No liste diffusion types available. <a href=":link">Add liste diffusion type</a>.',
      [':link' => Url::fromRoute('entity.broadcast_list_type.add_form')->toString()],
    );

    return $build;
  }

}
