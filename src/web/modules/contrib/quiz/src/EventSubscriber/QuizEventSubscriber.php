<?php

namespace Drupal\quiz\EventSubscriber;

use Drupal;
use Drupal\quiz\Entity\Quiz;
use Drupal\replicate\Events\AfterSaveEvent;
use Drupal\replicate\Events\ReplicateEntityEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuizEventSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      'replicate__after_save' => ['afterSave'],
    ];
  }

  /**
   * Copy questions to a new quiz revision.
   *
   * @param AfterSaveEvent $event
   */
  public function afterSave(AfterSaveEvent $event) {
    $entity = $event->getEntity();
    if ($entity->getEntityTypeId() == 'quiz') {
      $old_quiz = Drupal::entityTypeManager()
        ->getStorage('quiz')
        ->loadRevision($entity->old_vid);
      $entity->copyFromRevision($old_quiz);
    }
  }

}
