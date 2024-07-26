<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_search\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Orange yelen search routes.
 */
final class OrangeYelenSearchController extends ControllerBase {

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

}
