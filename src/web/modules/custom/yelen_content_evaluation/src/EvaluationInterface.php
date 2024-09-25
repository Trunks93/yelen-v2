<?php

declare(strict_types=1);

namespace Drupal\yelen_content_evaluation;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an evaluation entity type.
 */
interface EvaluationInterface extends ContentEntityInterface, EntityOwnerInterface {

}
