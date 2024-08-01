<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a trombino point service entity type.
 */
interface TrombinoPointServiceInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
