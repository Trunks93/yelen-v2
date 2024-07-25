<?php


namespace Drupal\Yelen_faq\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends ControllerBase
{
  public function index(Request $request){
    return [
      '#theme'=>'yelen_faq_page'
    ];
  }

}
