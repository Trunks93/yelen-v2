<?php


namespace Drupal\yelen_notification\Services;


use Drupal\user\Entity\User;
use Drupal\yelen_notification\Entity\BroadcastList;
use Drupal\yelen_notification\Services\ExtractMailer;

class EmailServices
{
  protected $extractor;

  public function __construct(ExtractMailer $extract)
  {
    $this->extractor = $extract;
  }

  /**
   * @param string $email
   * @param BroadcastList $listDiffusion
   * @return bool
   */
  public function inMailerList(string $email, BroadcastList $listDiffusion):bool{
    $name = $listDiffusion->label();
    $allEmails = $this->extractor->getEmailsFromBroadcastList($name);
    $user = \Drupal::currentUser();
    if(str_contains($allEmails,$email) || $user->hasRole('administrator') ){
      return true;
    }
    return false;
  }

}
