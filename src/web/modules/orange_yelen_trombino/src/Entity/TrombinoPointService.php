<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_trombino\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\orange_yelen_trombino\TrombinoPointServiceInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the trombino point service entity class.
 *
 * @ContentEntityType(
 *   id = "trombino_point_service",
 *   label = @Translation("Trombino - Point Service"),
 *   label_collection = @Translation("Trombino - Point Service"),
 *   label_singular = @Translation("trombino - point service"),
 *   label_plural = @Translation("trombino - points service"),
 *   label_count = @PluralTranslation(
 *     singular = "@count point service",
 *     plural = "@count points services",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\orange_yelen_trombino\TrombinoPointServiceListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\orange_yelen_trombino\Form\TrombinoPointServiceForm",
 *       "edit" = "Drupal\orange_yelen_trombino\Form\TrombinoPointServiceForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "trombino_point_service",
 *   admin_permission = "administer trombino_point_service",
 *   entity_keys = {
 *     "id" = "id",
 *     "name" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "collection" = "/admin/content/trombino-point-service",
 *     "add-form" = "/trombino-point-service/add",
 *     "canonical" = "/trombino-point-service/{trombino_point_service}",
 *     "edit-form" = "/trombino-point-service/{trombino_point_service}/edit",
 *     "delete-form" = "/trombino-point-service/{trombino_point_service}/delete",
 *     "delete-multiple-form" = "/admin/content/trombino-point-service/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.trombino_point_service.settings",
 * )
 */
final class TrombinoPointService extends ContentEntityBase implements TrombinoPointServiceInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      $this->setOwnerId(\Drupal::currentUser()->id());
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nom du point service'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 100)
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

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['trombino_point_service_types'],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -3
      ]);

    $fields['regions'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Regions'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['regions'],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2
      ]);

    $fields['situation_geographique'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Situation Géographique'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 1,
      ]);

    $fields['opening_days_hours'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Jours et heures d\'ouverture'))
      ->setSetting('max_length', 100)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 2,
      ]);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Contact téléphonique'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 3,
      ]);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Adresse email'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'email',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'weight' => 4,
      ]);

    $fields['services'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Services proposés'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['trombino_point_service_services'],
      ])
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ]);

    $fields['partner'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Partenaire'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['trombino_point_service_partners'],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2
      ])
      ->setRequired(FALSE);

    $fields['concept'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Concept'))
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => ['trombino_point_service_concept'],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'entity_reference_label',
        'weight' => -2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => -2
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the trombino point service was created.'))
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
      ->setDescription(t('The time that the trombino point service was last edited.'));

    return $fields;
  }

}
