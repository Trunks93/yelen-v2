<?php


namespace Drupal\yelen_notification\Event;


use Drupal\Component\EventDispatcher\Event;
use Drupal\yelen_notification\Entity\BroadcastList;

class BroadcastListEvent extends Event{

  const BROADCASTLIST_CREATE = 'broadcastList_create';
  const BROADCASTLIST_UPDATE = 'broadcastList_update';

  private string $broadcastlist;
  private BroadcastList|bool $entity;

  /**
   * @return bool|BroadcastList
   */
  public function getEntity(): bool|BroadcastList
  {
    return $this->entity;
  }

  /**
   * @param bool|BroadcastList $entity
   */
  public function setEntity(bool|BroadcastList $entity): void
  {
    $this->entity = $entity;
  }

  /**
   * @return string
   */
  public function getBroadcastlist(): string
  {
    return $this->broadcastlist;
  }

  /**
   * @param string $broadcastlist
   */
  public function setBroadcastlist(string $broadcastlist): void
  {
    $this->broadcastlist = $broadcastlist;
  }

}
