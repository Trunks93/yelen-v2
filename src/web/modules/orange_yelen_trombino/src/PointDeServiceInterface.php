<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a point de service entity type.
 */
interface PointDeServiceInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
