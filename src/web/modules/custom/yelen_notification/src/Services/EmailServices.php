<?php


namespace Drupal\yelen_notification\Services;


use Drupal\user\Entity\User;
use Drupal\yelen_notification\Entity\BroadcastList;

class EmailServices
{

  /**
   * @param string $email
   * @param BroadcastList $listDiffusion
   * @return bool
   */
  public function inMailerList(string $email, BroadcastList $listDiffusion):bool{
    $membres = $listDiffusion->field_membres->getValue();
    $ids = array_map(function($item){
      return $item['target_id'];
    },$membres);
    $users = User::loadMultiple($ids);
    foreach ($users as $user){
      if($user->getEmail() == $email || $user->hasRole('administrator')){
        return true;
      }
    }
    return false;
  }

}
