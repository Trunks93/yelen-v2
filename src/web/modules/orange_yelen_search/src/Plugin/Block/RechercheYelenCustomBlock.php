<?php

declare(strict_types=1);

namespace Drupal\orange_yelen_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;
/**
 * Provides a recherche yelen custom block.
 *
 * @Block(
 *   id = "orange_yelen_search_recherche_yelen_custom",
 *   admin_label = @Translation("Recherche Yelen Custom"),
 *   category = @Translation("Custom"),
 * )
 */
final class RechercheYelenCustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    // $form = \Drupal::formBuilder()->getForm(SearchApiAutocompleteForm::class, 'votre_nom_de_configuration_d_autocomplétion');
    return $build;
  }

}
