<?php

namespace Drupal\yelen_moderated_content\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends ControllerBase{

  public function unpublish(Node $node){
    $node->set('status',FALSE);
    $node->save();
    $this->messenger()->addMessage($this->t('Le contenu a bien été dépublier'));
    $url = Url::fromRoute('view.content.page_1');

    // Redirigez vers cette URL.
    return new RedirectResponse($url->toString());
  }

  public function publish(Node $node){
    $node->set('status',TRUE);
    $node->save();
    $this->messenger()->addMessage($this->t('Le contenu a bien été publier'));
    $url = Url::fromRoute('view.content.page_1');

    // Redirigez vers cette URL.
    return new RedirectResponse($url->toString());
  }

}
