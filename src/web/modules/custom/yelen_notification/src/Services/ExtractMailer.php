<?php


namespace Drupal\yelen_notification\Services;


use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\yelen_notification\Entity\BroadcastList;

class ExtractMailer
{

  /**
   * Permet d'extraire les adresses Emails d'une liste de diffusion (depuis un contenu)
   * @param $node
   * @return array
   */
  public function extractMailFromBroadcastList(EntityInterface $entity,string $field): array
  {
    $emailIds = [];
    try{
      $mailingLists = $entity->get($field)->getValue();
    }catch (\Exception $e){
      \Drupal::logger('Error Notification')->error($entity->label().' - '.$e->getMessage());
    }


    foreach ($mailingLists as $mailingList) {
      $emailEntity = BroadcastList::load($mailingList['target_id']);
      $emailIds[] = $emailEntity->get('field_membres')->getValue();
    }
    foreach ($emailIds as $emailId) {
      $emails[] = $this->getEmailAddress($emailId);
    }

    return $emails;
  }

  /**
   * Extrait l'adresse email d'un utilisateur drupal via l'ID
   * @param $userIds
   * @return string
   */
  public function getEmailAddress($userIds):string
  {
    $emails = [];
    foreach ($userIds as $userId) {
      $user = User::load($userId['target_id']);
      if($user instanceof User){
        $emails[] = $user->getEmail();
      }else{
        \Drupal::logger('Notification Email')->error('erreur avec cet utilisateur '.$userId['target_id']);
        \Drupal::logger('Notification Email')->error('Détail utilisateur à problème'.$user->getAccountName());
      }

    }
    return implode(' , ',$emails);
  }

  /**
   * Extrait une liste d'adresse emails en fonction du nom de la liste de diffusion
   */
  public function getEmailsFromBroadcastList($broadcastListName){
    $broadcastList = \Drupal::entityTypeManager()
      ->getStorage('broadcast_list')
      ->loadByProperties(['label'=>$broadcastListName]);
    $users = current($broadcastList)->field_membres->getValue();
    $emails = $this->getEmailAddress($users);

    return $emails;
  }

}
