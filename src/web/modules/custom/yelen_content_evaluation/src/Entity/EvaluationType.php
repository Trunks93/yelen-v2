<?php

declare(strict_types=1);

namespace Drupal\yelen_content_evaluation\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Evaluation type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "evaluation_type",
 *   label = @Translation("Evaluation type"),
 *   label_collection = @Translation("Evaluation types"),
 *   label_singular = @Translation("evaluation type"),
 *   label_plural = @Translation("evaluations types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count evaluations type",
 *     plural = "@count evaluations types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\yelen_content_evaluation\Form\EvaluationTypeForm",
 *       "edit" = "Drupal\yelen_content_evaluation\Form\EvaluationTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\yelen_content_evaluation\EvaluationTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer evaluation types",
 *   bundle_of = "evaluation",
 *   config_prefix = "evaluation_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/evaluation_types/add",
 *     "edit-form" = "/admin/structure/evaluation_types/manage/{evaluation_type}",
 *     "delete-form" = "/admin/structure/evaluation_types/manage/{evaluation_type}/delete",
 *     "collection" = "/admin/structure/evaluation_types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   },
 * )
 */
final class EvaluationType extends ConfigEntityBundleBase {

  /**
   * The machine name of this evaluation type.
   */
  protected string $id;

  /**
   * The human-readable name of the evaluation type.
   */
  protected string $label;

}
