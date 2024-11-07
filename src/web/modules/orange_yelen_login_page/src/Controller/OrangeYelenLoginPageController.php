<?php

namespace Drupal\orange_yelen_login_page\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OrangeYelenLoginPageController extends ControllerBase{
  public function checkAccess()
  {
    return $this->redirect('<front>');
  }
}
