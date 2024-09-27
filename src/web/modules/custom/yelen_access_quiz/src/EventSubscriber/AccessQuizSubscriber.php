<?php


namespace Drupal\yelen_access_quiz\EventSubscriber;


use Drupal\Core\Access\AccessResult;
use Drupal\Core\Site\Settings;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;

class AccessQuizSubscriber extends RouteSubscriberBase //implements EventSubscriberInterface
{
  protected function alterRoutes(RouteCollection $collection)
  {
      $quiz_route = $collection->get('entity.quiz.take');
      $question_route = $collection->get('quiz.question.take');
      $quiz_route->setRequirements([
        '_custom_access' => 'Drupal\yelen_access_quiz\Controller\AccessQuizController::has_access'
      ]);

      $question_route->setRequirements([
        '_custom_access' => 'Drupal\yelen_access_quiz\Controller\AccessQuizController::has_access'
      ]);
    }

}
