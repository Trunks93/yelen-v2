<?php

declare(strict_types=1);

namespace Drupal\yelen_notification;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a liste diffusion entity type.
 */
interface BroadcastListInterface extends ContentEntityInterface, EntityChangedInterface {

}
