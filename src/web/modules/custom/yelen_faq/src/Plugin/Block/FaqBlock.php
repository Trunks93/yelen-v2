<?php

namespace Drupal\yelen_faq\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a faq_block block.
 *
 * @Block(
 *   id = "yelen_faq_block",
 *   admin_label = @Translation("yelen_faq_block"),
 *   category = @Translation("Custom"),
 * )
 */
class FaqBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }


  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
    );
  }


  public function build(): array {

    $faqs = $this->entityTypeManager->getStorage('node')
      ->loadByProperties(["type"=>'faq','status'=>true]);

    $build['content'] = [
      '#theme' => "yelen_faq_block",
      '#content'=>[
        'faqs'=>$faqs
      ],
    ];
    return $build;
  }

}
