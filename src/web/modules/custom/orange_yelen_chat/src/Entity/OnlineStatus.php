<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\UserInterface;

/**
 * Defines the online status chat entity class.
 *
 * @ContentEntityType(
 *   id = "orange_yelen_chat_online_status",
 *   label = @Translation("Yelen Chat Online Status"),
 *   label_collection = @Translation("Yelen Chat Online Status"),
 *   label_singular = @Translation("Yelen Chat Online Status"),
 *   label_plural = @Translation("Yelen Chat Online Status"),
 *   label_count = @PluralTranslation(
 *      singular = "@count Yelen Chat Online Status",
 *      plural = "@count Yelen Chat Online Status",
 *   ),
 *   base_table = "orange_yelen_chat_online_status",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *     "uid" = "uid"
 *   },
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\orange_yelen_chat\Access\OrangeYelenChatAccessControlHandler",
 *   },
 *   admin_permission = "administer orange_yelen_chat_online_status",
 * )
 */
final class OnlineStatus extends ContentEntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDescription(t('The user associated with this online status.'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE);

    $fields['last_active'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Last Active'))
      ->setDescription(t('The time the user was last active.'))
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDescription(t('The online status.'))
      ->setDefaultValue('offline')
      ->setSettings([
        'max_length' => 32,
        'text_processing' => 0,
      ]);

    return $fields;
  }

  public function getUser(): ?UserInterface {
    return $this->get('uid')->entity;
  }

  public function getLastActive(): int {
    return $this->get('last_active')->value;
  }

  public function getStatus(): string {
    return $this->get('status')->value;
  }
}
