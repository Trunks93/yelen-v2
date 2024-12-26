<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\orange_yelen_chat\MessageInterface;
use Drupal\user\UserInterface;

/**
 * Defines the message chat entity class.
 *
 * @ContentEntityType(
 *   id = "orange_yelen_chat_message",
 *   label = @Translation("Message Chat"),
 *   label_collection = @Translation("Message Chats"),
 *   label_singular = @Translation("message chat"),
 *   label_plural = @Translation("message chats"),
 *   label_count = @PluralTranslation(
 *     singular = "@count message chats",
 *     plural = "@count message chats",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\orange_yelen_chat\MessageListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\orange_yelen_chat\Access\OrangeYelenChatAccessControlHandler",
 *   },
 *   base_table = "orange_yelen_chat_message",
 *   admin_permission = "administer orange_yelen_chat_message",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 *   field_ui_base_route = "entity.orange_yelen_chat_message.settings",
 * )
 */
final class Message extends ContentEntityBase implements MessageInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['conversation'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Conversation'))
      ->setDescription(t('La conversation à laquelle ce message appartient.'))
      ->setSetting('target_type', 'orange_yelen_chat_conversation')
      ->setRequired(TRUE);

    $fields['user'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDescription(t('L\'utilisateur qui a envoyé le message.'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE);

    $fields['message'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Message'))
      ->setDescription(t('Le contenu du message.'))
      ->setRequired(TRUE);

    $fields['read'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Read'))
      ->setDescription(t('Détermine si le message a été lu ou non'))
      ->setDefaultValue(FALSE);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDescription(t('Le statut du message.'))
      ->setSettings([
        'allowed_values' => [
          'pending' => 'En attente',
          'sended' => 'Délivré',
          'deleted' => 'Supprimé',
        ],
        'max_length' => 32,
        'text_processing' => 0,
      ])
      ->setDefaultValue('active')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('La date à laquelle le message a été crée'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  public function getConversation(): ?Conversation {
    return $this->get('conversation')->entity;
  }

  public function getUser(): ?UserInterface {
    return $this->get('user')->entity;
  }

  public function getMessage(): string {
    return $this->get('message')->value;
  }

  public function getCreatedTime(): string {
    return $this->get('created')->value;
  }

  public function isRead(): bool {
    return (bool) $this->get('read')->value;
  }

}
