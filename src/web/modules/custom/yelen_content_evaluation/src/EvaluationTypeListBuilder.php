<?php

declare(strict_types=1);

namespace Drupal\yelen_content_evaluation;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of evaluation type entities.
 *
 * @see \Drupal\yelen_content_evaluation\Entity\EvaluationType
 */
final class EvaluationTypeListBuilder extends ConfigEntityListBuilder {

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
      'No evaluation types available. <a href=":link">Add evaluation type</a>.',
      [':link' => Url::fromRoute('entity.evaluation_type.add_form')->toString()],
    );

    return $build;
  }

}
