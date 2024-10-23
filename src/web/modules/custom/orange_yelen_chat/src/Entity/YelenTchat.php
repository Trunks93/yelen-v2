<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_chat\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\orange_yelen_chat\YelenTchatInterface;

/**
 * Defines the yelen tchat entity class.
 *
 * @ContentEntityType(
 *   id = "orange_yelen_chat",
 *   label = @Translation("Yelen Tchat"),
 *   label_collection = @Translation("Yelen Tchats"),
 *   label_singular = @Translation("yelen tchat"),
 *   label_plural = @Translation("yelen tchats"),
 *   label_count = @PluralTranslation(
 *     singular = "@count yelen tchats",
 *     plural = "@count yelen tchats",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\orange_yelen_chat\YelenTchatListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\orange_yelen_chat\YelenTchatAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\orange_yelen_chat\Form\YelenTchatForm",
 *       "edit" = "Drupal\orange_yelen_chat\Form\YelenTchatForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "orange_yelen_chat",
 *   admin_permission = "administer orange_yelen_chat",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/orange-yelen-chat",
 *     "add-form" = "/yelen-chat/add",
 *     "canonical" = "/yelen-chat/{orange_yelen_chat}",
 *     "edit-form" = "/yelen-chat/{orange_yelen_chat}/edit",
 *     "delete-form" = "/yelen-chat/{orange_yelen_chat}/delete",
 *     "delete-multiple-form" = "/admin/content/orange-yelen-chat/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.orange_yelen_chat.settings",
 * )
 */
final class YelenTchat extends ContentEntityBase implements YelenTchatInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(FALSE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['visitor_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Id Visiteur'))
      ->setDescription(t('L\'ID du visiteur qui a initié la discussion'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['administrator_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Id Administrateur'))
      ->setDescription(t('L\'ID de l\'administrateur ou gestionnaire de base de connaissance assigné à la discussion'))
      ->setSetting('target_type', 'user')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 1,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['message'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Message'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string_textarea',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Status'))
      ->setRequired(TRUE)
      ->setSettings([
        'allowed_values' => [
          'pending' => 'En attente',
          'active' => 'En cours',
          'closed' => 'Clôturée',
          'deleted' => 'Supprimée',
        ],
      ])
      ->setDefaultValue('pending')
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

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the yelen tchat was created.'))
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
      ->setDescription(t('The time that the yelen tchat was last edited.'));

    return $fields;
  }

}
