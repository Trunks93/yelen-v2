<?php

declare(strict_types=1);

namespace Drupal\yelen_notification\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yelen_notification\BroadcastListInterface;

/**
 * Defines the liste diffusion entity class.
 *
 * @ContentEntityType(
 *   id = "broadcast_list",
 *   label = @Translation("Liste diffusion"),
 *   label_collection = @Translation("Liste diffusions"),
 *   label_singular = @Translation("liste diffusion"),
 *   label_plural = @Translation("liste diffusions"),
 *   label_count = @PluralTranslation(
 *     singular = "@count liste diffusions",
 *     plural = "@count liste diffusions",
 *   ),
 *   bundle_label = @Translation("Liste diffusion type"),
 *   handlers = {
 *     "list_builder" = "Drupal\yelen_notification\BroadcastListListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\yelen_notification\Form\BroadcastListForm",
 *       "edit" = "Drupal\yelen_notification\Form\BroadcastListForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "broadcast_list",
 *   admin_permission = "administer broadcast_list types",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bundle",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/broadcast-list",
 *     "add-form" = "/liste-de-diffusion/add/{broadcast_list_type}",
 *     "add-page" = "/liste-de-diffusion/add",
 *     "canonical" = "/liste-de-diffusion/{broadcast_list}",
 *     "edit-form" = "/liste-de-diffusion/{broadcast_list}/edit",
 *     "delete-form" = "/liste-de-diffusion/{broadcast_list}/delete",
 *     "delete-multiple-form" = "/admin/content/broadcast-list/delete-multiple",
 *   },
 *   bundle_entity_type = "broadcast_list_type",
 *   field_ui_base_route = "entity.broadcast_list_type.edit_form",
 * )
 */
final class BroadcastList extends ContentEntityBase implements BroadcastListInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
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

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the liste diffusion was created.'))
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
      ->setDescription(t('The time that the liste diffusion was last edited.'));

    return $fields;
  }

}
