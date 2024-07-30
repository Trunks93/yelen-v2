<?php


namespace Drupal\yelen_notification\Services;


use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\yelen_notification\Entity\BroadcastList;

class ExtractMailer
{

  /**
   * Permet d'extraire les adresses Emails d'une liste de diffusion
   * @param $node
   * @return array
   */
  public function extractMailFromBroadcastList(Node $node): array
  {
    $emailIds = [];
    $mailingLists = $node->get('field_liste_de_diffusion')->getValue();

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
   * @param $emailIds
   * @return string
   */
  public function getEmailAddress($emailIds):string
  {
    $emails = [];
    foreach ($emailIds as $emailId) {
      $user = User::load($emailId['target_id']);
      $emails[] = $user->getEmail();
    }
    return implode(', ',$emails);
  }


}
