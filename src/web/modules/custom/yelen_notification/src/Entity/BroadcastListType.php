<?php

declare(strict_types=1);

namespace Drupal\yelen_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Liste diffusion type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "broadcast_list_type",
 *   label = @Translation("Liste diffusion type"),
 *   label_collection = @Translation("Liste diffusion types"),
 *   label_singular = @Translation("liste diffusion type"),
 *   label_plural = @Translation("liste diffusions types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count liste diffusions type",
 *     plural = "@count liste diffusions types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\yelen_notification\Form\BroadcastListTypeForm",
 *       "edit" = "Drupal\yelen_notification\Form\BroadcastListTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\yelen_notification\BroadcastListTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer broadcast_list types",
 *   bundle_of = "broadcast_list",
 *   config_prefix = "broadcast_list_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/broadcast_list_types/add",
 *     "edit-form" = "/admin/structure/broadcast_list_types/manage/{broadcast_list_type}",
 *     "delete-form" = "/admin/structure/broadcast_list_types/manage/{broadcast_list_type}/delete",
 *     "collection" = "/admin/structure/broadcast_list_types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   },
 * )
 */
final class BroadcastListType extends ConfigEntityBundleBase {

  /**
   * The machine name of this liste diffusion type.
   */
  protected string $id;

  /**
   * The human-readable name of the liste diffusion type.
   */
  protected string $label;

}
