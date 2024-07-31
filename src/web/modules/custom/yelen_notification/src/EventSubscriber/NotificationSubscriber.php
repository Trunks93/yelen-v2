<?php


namespace Drupal\yelen_notification\EventSubscriber;

use Drupal\Core\Messenger\Messenger;
use Drupal\Core\StackMiddleware\Session;
use Drupal\Core\Url;
use Drupal\yelen_notification\Event\NotificationEvent;
use Drupal\yelen_notification\Services\SendEmailNotification;
use Drupal\yelen_notification\Constante\NotificationSubjectPrefix;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\symfony_mailer\Address as EmailAddress;

class NotificationSubscriber implements EventSubscriberInterface
{

  protected $sendmail;

  public function __construct(SendEmailNotification $sendmail)
  {
    $this->sendmail = $sendmail;
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents(): array
  {
    return [
      NotificationEvent::CREATION_CONTENT_PUBLIC => 'sendPublicNotification',
    ];
  }

  public function sendPublicNotification(NotificationEvent $event)
  {

    $url = Url::fromRoute('entity.node.canonical', ['node' => $event->getNodeId()])->setAbsolute()->toString();
    $firstEmail = $event->getEmailAddress();

    $to = current(explode(', ', $firstEmail));
    $cc = $event->getEmailAddress();
    $subject = NotificationSubjectPrefix::CREATION_CONTENT . ': ' . strtoupper($event->getNodeContentType());
    $templateHtml = [
      '#theme' => 'mailer',
      '#content' => [
        'node_title' => $event->getNodeTitle(),
        'node_url' => $url,
      ]
    ];
    $this->sendmail->sendNotification($subject, $to, $cc, $templateHtml);
  }


}
