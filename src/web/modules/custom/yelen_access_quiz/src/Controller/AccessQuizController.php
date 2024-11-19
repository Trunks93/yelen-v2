<?php

namespace Drupal\yelen_access_quiz\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\yelen_notification\Entity\BroadcastList;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class AccessQuizController
{
  public function has_access()
  {
    $currentUser = \Drupal::currentUser();
    $request = \Drupal::request();
    $quiz = $request->get('quiz');
    if ($currentUser->id() !== 0) {
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
              \Drupal::logger('access-quiz')->info('Access granted for ' . $currentUser->getAccountName());
              return AccessResult::allowed();
            }
          }
          \Drupal::logger('access-quiz')->info('Access Forbidden for ' . $currentUser->getAccountName());
          return AccessResult::forbidden("Vous n'avez pas accès à ce QUIZ !");
        }
      } catch (\Exception $e) {
        \Drupal::logger('access-quiz')->error('Exception ' . $e);
      }
    }
    \Drupal::logger('access-quiz')->info('Access Forbidden for ' . $currentUser->getAccountName());
    return AccessResult::forbidden("Vous n'êtes pas authentifié. Veuillez vous connecter à Yelen !!");
  }
}
