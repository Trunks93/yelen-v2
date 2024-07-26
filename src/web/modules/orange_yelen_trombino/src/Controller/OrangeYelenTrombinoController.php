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
      '#markup' => $this->t('Trombino'),
    ];
  }
}
