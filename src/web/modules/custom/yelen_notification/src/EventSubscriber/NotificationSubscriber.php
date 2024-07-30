<?php


namespace Drupal\yelen_notification\EventSubscriber;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Url;
use Drupal\yelen_notification\Event\NotificationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\symfony_mailer\Address as EmailAddress;

class NotificationSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
  public static function getSubscribedEvents(): array
  {
    return [
      NotificationEvent::CREATION_CONTENT_PUBLIC => 'sendPublicNotification',
    ];
  }

  public function sendPublicNotification(NotificationEvent $event){

    $url = Url::fromRoute('entity.node.canonical', ['node' => $event->getNodeId()])->setAbsolute()->toString();
    $firstEmail = $event->getEmailAddress();

    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'yelen_notification';
    $key = 'content_create'; // Replace with Your key
    $to = current(explode(', ',$firstEmail));
    $body_data = [
      '#theme' => 'mailer',
      '#content' => [
        'node_title'=>$event->getNodeTitle(),
        'node_url'=> $url,
      ]
    ];
    $params['message'] = \Drupal::service('renderer')->render($body_data);

    $params['content_type'] = $event->getNodeContentType();
    $params['headers']['Cc'] = $event->getEmailAddress();
    $params['headers']['content-type'] = 'text/html';

    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = true;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] == true) {
      \Drupal::logger('yelen_notification')->info("Notification envoyé à ".$event->getEmailAddress());
    }else{
      \Drupal::logger('yelen_notification')->error("Notification non transmise !!");
    }


  }



}
