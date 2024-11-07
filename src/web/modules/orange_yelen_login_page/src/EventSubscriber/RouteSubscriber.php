<?php

namespace Drupal\orange_yelen_login_page\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('user.pass');
    $routePost = $collection->get('user.pass.http');
    $route->setDefault('_controller','Drupal\orange_yelen_login_page\Controller\OrangeYelenLoginPageController::checkAccess');
    $routePost->setDefault('_controller','Drupal\orange_yelen_login_page\Controller\OrangeYelenLoginPageController::checkAccess');
  }

}
