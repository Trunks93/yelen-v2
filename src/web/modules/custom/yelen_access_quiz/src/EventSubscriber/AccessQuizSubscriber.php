<?php


namespace Drupal\yelen_access_quiz\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class AccessQuizSubscriber extends RouteSubscriberBase //implements EventSubscriberInterface
{
  protected function alterRoutes(RouteCollection $collection)
  {
      $quiz_route = $collection->get('entity.quiz.take');
      $question_route = $collection->get('quiz.question.take');
      $quiz_canonical = $collection->get('entity.quiz.canonical');

      $quiz_route->setRequirements([
        '_custom_access' => 'Drupal\yelen_access_quiz\Controller\AccessQuizController::has_access'
      ]);

      $question_route->setRequirements([
        '_custom_access' => 'Drupal\yelen_access_quiz\Controller\AccessQuizController::has_access'
      ]);

      $quiz_canonical->setRequirements([
        '_custom_access' => 'Drupal\yelen_access_quiz\Controller\AccessQuizController::has_access'
      ]);
    }

}
