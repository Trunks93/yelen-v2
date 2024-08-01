<?php


namespace Drupal\yelen_notification\Services;


class SendEmailNotification
{

  const MODULE_NAME = 'yelen_notification';

  /**
   * @param string $subject
   * @param string $to emails separated with coma
   * @param string|null $cc emails separated with coma
   * @param array|null $templateHtml
   * @return bool
   */
  public function sendNotification(string $subject, string $to, string $cc = null, array $templateHtml = null):bool
  {
    try {
      $mailManager = \Drupal::service('plugin.manager.mail');
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $params = [];
      $params['headers']['content-type'] = 'text/html';
      if ($cc !== null) {
        $params['headers']['Cc'] = $cc;
      }
      if ($templateHtml != null) {
        $params['message'] = \Drupal::service('renderer')->render($templateHtml);
      }
      $params['subject'] = $subject;

      $result =  $mailManager->mail(self::MODULE_NAME, self::MODULE_NAME, $to, $langcode, $params, NULL, true);
      $this->logSendNotification($result,$subject);
    }catch (\Exception $e){
      $this->logSendNotification([],$subject,$e->getMessage());
      $result = false;
    }
    return $result;
  }

  /**
   * Logging success or failure sending email
   * @param array $result
   * @param $subject
   * @param null $error
   */
  private function logSendNotification(array $result,$subject, $error = null){
    if ($result['result'] == true) {
      \Drupal::logger('yelen_notification')->info("Notification envoyé pour : ".strtoupper($subject));
    }else{
      if($error instanceof \Exception){
        \Drupal::logger('yelen_notification')->critical($error->getMessage());

      }else{
        \Drupal::logger('yelen_notification')->error("Erreur notification pour : ".strtoupper($subject));
      }
    }
  }

}
