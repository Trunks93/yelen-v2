<?php

namespace Drupal\yelen_access_quiz\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\yelen_notification\Entity\BroadcastList;
use Symfony\Component\HttpFoundation\Request;


class AccessQuizController
{
  public function has_access()
  {
    $currentUser = \Drupal::currentUser();
    $request = \Drupal::request();
    $quiz = $request->get('quiz');
    try {
       $request = \Drupal::request();
        $route_match = \Drupal::service('router.no_access_checks')->match($request->getPathInfo());
        if ($route_match['_route'] == "entity.quiz.take" || $route_match['_route'] == "quiz.question.take") {
          $broadCastLists = $quiz->field_liste_de_diffusion->getValue();
          $emailService = \Drupal::service('yelen_notification.emailFinder');
          foreach ($broadCastLists as $broadCastList) {
            $listDiffusionEntity = BroadcastList::load($broadCastList['target_id']);
            $response = $emailService->inMailerList($currentUser->getEmail(), $listDiffusionEntity);
            if ($response == true) {
              return AccessResult::allowed();
            }
          }
          return AccessResult::forbidden();
        }
    }catch (\Exception $e){
    }
    return AccessResult::forbidden();
  }
}
