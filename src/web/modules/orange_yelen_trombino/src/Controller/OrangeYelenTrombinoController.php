<?php
namespace Drupal\orange_yelen_trombino\Controller;

use Drupal\Core\Controller\ControllerBase;

class OrangeYelenTrombinoController extends ControllerBase{

  /*
   * Page d'accueil Trombino
   * @return array
   */
  public function index()
  {
    return [
      '#theme' => 'orange_yelen_trombino_index',
      '#attached' => [
        'library' => [
          'orange_yelen_trombino/orange-yelen-trombino',
        ],
      ],
    ];
  }
}
