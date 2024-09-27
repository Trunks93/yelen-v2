<?php

namespace Drupal\yelen_access_quiz\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\yelen_notification\Entity\BroadcastList;
use Symfony\Component\HttpFoundation\Request;


class AccessQuizController
{
./
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


  /*public function sso_access()
  {
    \Drupal::logger('sso_auth')->info("in sso check.");

    $request = \Drupal::request();
    $access =$request->headers->get('access');
    $refresh =$request->headers->get('refresh');
    if(!$access && !$refresh){
      \Drupal::logger('sso_auth')->info("no refresh and access");
      return AccessResult::allowed();
    }
    $access_token = $this->security->decodePassword($access);

    $refresh_token = $this->security->decodePassword($refresh);

    if($access_token=="" && $refresh_token==""){
      \Drupal::logger('sso_auth')->info("refresh and access= '' ");
      return AccessResult::allowed();
    }
    \Drupal::logger('sso_auth')->info('ACCESS TOKEN: '.$access_token);
    $userInfo = $this->identity->getUserInfo($access_token);

    if(empty($userInfo)){
      \Drupal::logger('sso_auth')->info("empty user info");
      $tokens = $this->identity->getTokenSso($refresh_token);
      if(!empty($tokens)){
        $userInfo = $this->identity->getUserInfo($tokens['access_token']);
      }
    }
    if (\Drupal::currentUser()->isAnonymous() && !empty($userInfo) ) {
      \Drupal::logger('sso_auth')->info("user is anonymous with incoming userInfo");
      // on verifie si l'utilisateur est inscrit, si oui on l'authentifie
      $userState = $this->auth_service->is_registered($userInfo['phone_number']);
      if(!$userState){
        //sinon on l'inscrit et on l'autentifie
        $register_status = $this->auth_service->register($userInfo);
        \Drupal::logger('sso_auth')->info("register and login user : ".intval($register_status));
        return $register_status == true ? AccessResult::allowed() : AccessResult::forbidden();
      }
      return AccessResult::allowed();
    }else{
      $currentUser = \Drupal::currentUser();
      if($currentUser->getAccountName() == $userInfo['phone_number']){
        \Drupal::logger('sso_auth')->info("It's the good user.");
        //on s'assure que le current drupal User est bien le user dont on a recupérer le userinfo
        return AccessResult::allowed();
      }else{
        //sans doute qu'il ya un soucis de cache donc j'authentifie le bon user
        $userState = $this->auth_service->is_registered($userInfo['phone_number']);
        if(!$userState){
          //sinon on l'inscrit et on l'autentifie
          $register_status = $this->auth_service->register($userInfo);
          \Drupal::logger('sso_auth')->info("register and login user : ".intval($register_status));
          return $register_status == true ? AccessResult::allowed() : AccessResult::forbidden();
        }
        return AccessResult::allowed();
      }

      \Drupal::logger('sso_auth')->info('USER ACCOUNT NAME: '.$currentUser->getAccountName());
      \Drupal::logger('sso_auth')->info('USER ACCOUNT Email: '.$currentUser->getEmail());
      \Drupal::logger('sso_auth')->info('USER_INFO'.json_encode($userInfo));
      //si oui on lui authorise la requete sinon FORBIDDEN
      return AccessResult::forbidden();
    }
  }*/
}
