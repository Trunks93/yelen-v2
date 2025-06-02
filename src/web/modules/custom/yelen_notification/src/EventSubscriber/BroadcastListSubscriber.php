<?php


namespace Drupal\yelen_notification\EventSubscriber;


use Drupal\yelen_notification\Constante\Constante;
use Drupal\yelen_notification\Entity\BroadcastList;
use Drupal\yelen_notification\Event\BroadcastListEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BroadcastListSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
          BroadcastListEvent::BROADCASTLIST_CREATE => 'createBroadcastList',
          BroadcastListEvent::BROADCASTLIST_UPDATE => 'updateBroadcastList'
        ];
    }

    public function createBroadcastList(BroadcastListEvent $event){
      $broadcastListName = $event->getBroadcastlist();
      if(str_contains($broadcastListName,'admins')){
        $user_ids = $this->getOrangeUsers([],Constante::ADMINISTRATEUR);
      }else{
        $user_ids = $this->getOrangeUsers();
      }
      $members = [];
      foreach ($user_ids as $user_id) {
        $members[] = ['target_id' => $user_id];
      }
      $list = BroadcastList::create([
        'label' => $broadcastListName,
        'status' => TRUE,
        'bundle' => 'normal',
        'field_membres' => $members
      ]);
      $list->save();
    }

    public function updateBroadcastList(BroadcastListEvent $event)
    {
      $broadcastListName = $event->getBroadcastlist();
      $entity = $event->getEntity();
      $currentMember = $entity->field_membres->getValue();
      if(str_contains($broadcastListName,'admins')){
        $user_ids = $this->getOrangeUsers($currentMember,Constante::ADMINISTRATEUR);
      }else{
        $user_ids = $this->getOrangeUsers($currentMember);
      }
      $position = count($currentMember);
      foreach ($user_ids as $user_id) {
        $entity->get('field_membres')->set($position, ['target_id' => $user_id]);
        $position++;
      }
      $entity->save();
    }

    private function getOrangeUsers(array $existingMembers = [], $role="user"){
      $em = \Drupal::entityTypeManager()->getStorage('user');
      $users = $em->getQuery()
        ->accessCheck(TRUE)
        ->condition('status', true)
        ->condition('mail', '%orange%', 'LIKE')
        ->condition('mail','%yelen-oci.orange.ci%','NOT LIKE');
      if ($role == Constante::ADMINISTRATEUR) {
        $users->condition('roles', $role, 'NOT IN');
      }
      if (!empty($existingMembers)) {
        $list = [];
        foreach ($existingMembers as $member) {
          $list[] = $member['target_id'];
        }
        $users->condition('uid', $list, 'NOT IN');
      }

      return $users->execute();
    }
}
