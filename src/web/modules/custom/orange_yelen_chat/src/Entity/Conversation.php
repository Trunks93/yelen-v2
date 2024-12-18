<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\orange_yelen_chat\ConversationInterface;
use Drupal\user\UserInterface;

/**
 * Defines the conversation entity class.
 *
 * @ContentEntityType(
 *   id = "orange_yelen_chat_conversation",
 *   label = @Translation("Conversation"),
 *   label_collection = @Translation("Conversations"),
 *   label_singular = @Translation("conversation"),
 *   label_plural = @Translation("conversations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count conversations",
 *     plural = "@count conversations",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\orange_yelen_chat\ConversationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\orange_yelen_chat\Access\OrangeYelenChatAccessControlHandler",
 *   },
 *   base_table = "orange_yelen_chat_conversation",
 *   admin_permission = "administer orange_yelen_chat_conversation",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 *   field_ui_base_route = "entity.orange_yelen_chat_conversation.settings",
 * )
 */
final class Conversation extends ContentEntityBase implements ConversationInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDescription(t('Le statut de la conversation.'))
      ->setSettings([
        'allowed_values' => [
          'active' => 'En cours',
          'closed' => 'Clôturée',
          'deleted' => 'Supprimée',
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
        'type' => 'list_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created_by'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Created by'))
      ->setDescription(t('L\'utilisateur qui a crée la conversation.'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE);

    $fields['participant'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Participant'))
      ->setDescription(t('Le destinataire de la conversation.'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('La date à laquelle la conversation a été créée.'))
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

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('La date à laquelle la conversation a été mise à jour.'));

    return $fields;
  }

  public function getCreatedBy(): ?UserInterface {
    return $this->get('created_by')->entity;
  }

  public function getParticipant(): ?UserInterface {
    return $this->get('participant')->entity;
  }
  public function getCreatedTime(): ?UserInterface {
    return $this->get('created')->entity;
  }
  public function getChangedTime(): ?UserInterface {
    return $this->get('changed')->entity;
  }

  public function getStatus(): string
  {
    return $this->get('status')->value;
  }

}
