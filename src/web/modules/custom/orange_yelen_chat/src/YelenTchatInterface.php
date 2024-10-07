<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a yelen tchat entity type.
 */
interface YelenTchatInterface extends ContentEntityInterface, EntityChangedInterface {

}
