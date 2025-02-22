<?php

declare(strict_types=1);

namespace Drupal\yelen_migration\EventSubscriber;

use Drupal\feeds\Event\EntityEvent;
use Drupal\feeds\Event\FeedsEvents;
use Drupal\feeds\Exception\EmptyFeedException;
use Drupal\file\Entity\File;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @todo Add description for this subscriber.
 */
final class YelenMigrationFeedsSubscriber implements EventSubscriberInterface {

  /**
   * Acts on presaving an entity.
   */
  public function presave(EntityEvent $event) {
    $node = $event->getEntity();
    $item = $event->getItem();

    \Drupal::logger('yelen_migration')->info("Importation Chemin Image Illustration 1 {$item->get('chemin_image_illustration')}");

    /*
    $image_field_name = 'chemin_image_illustration';
    $document_field_name = 'chemin_document';

    $image_filename = $item->get($image_field_name) ?? '';
    $document_filename = $item->get($document_field_name) ?? '';
    $string_to_replace = 'https://yelenprod/sites/default/files/';

    // Example: skip nodes if the item's 'blocked' value evaluates to true.
    if (!empty($image_filename)) {
      // Construire le chemin du fichier dans le dossier alpha_v1
      $image_file_uri = 'public://yelen_v1/' . str_replace($string_to_replace, '', $image_filename);
      \Drupal::logger('yelen_migration')->info("Importation - Image URI {$image_file_uri}");
      // Vérifier si le fichier existe déjà dans Drupal
      $file = $this->get_existing_file($image_file_uri);
      \Drupal::logger('yelen_migration')->info("Importation - Image File %file", ['%file' => $file]);

      if ($file) {
        // Attacher le fichier existant au champ
        $item->set($image_field_name, ['target_id' => $file->id()]);
        \Drupal::logger('yelen_migration')->info('Fichier Image attaché : @file', ['@file' => $image_file_uri]);
      } else {
        \Drupal::logger('yelen_migration')->warning('Le fichier Image @file n’existe pas dans public://yelen_v1/', ['@file' => $image_file_uri]);
      }
    }

    if (!empty($document_filename)) {
      // Construire le chemin du fichier dans le dossier alpha_v1
      $document_file_uri = 'public://yelen_v1/' . str_replace($string_to_replace, '', $document_filename);

      // Vérifier si le fichier existe déjà dans Drupal
      $file = $this->get_existing_file($document_file_uri);

      if ($file) {
        // Attacher le fichier existant au champ
        $item->set($document_field_name, ['target_id' => $file->id()]);
        \Drupal::logger('yelen_migration')->info('Fichier Document attaché : @file', ['@file' => $document_file_uri]);
      } else {
        \Drupal::logger('yelen_migration')->warning('Le fichier Document @file n’existe pas dans public://yelen_v1/', ['@file' => $document_file_uri]);
      }
    }

    /*
    if ($item->get('blocked')) {
      if (!$node->isNew()) {
        // Remove node.
        $node->delete();
      }

      // Prevent this node from being saved.
      throw new EmptyFeedException();
    }*/
  }

  /**
   * Acts on postsaving an entity.
   */
  public function postsave(EntityEvent $event) {
    $node = $event->getEntity();
    $item = $event->getItem();
    \Drupal::logger('yelen_migration')->info('Import Post Save of Item: @item And Node %node', ['@item' => $item, '%node' => $node]);
    // @todo implement your logic here.
  }


  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      FeedsEvents::PROCESS_ENTITY_PRESAVE => ['presave'],
      FeedsEvents::PROCESS_ENTITY_POSTSAVE => ['postsave'],
    ];
  }


  private function get_existing_file(string $file_uri) {
    // Vérifier si le fichier physique existe
    $file_real_uri = \Drupal::service('file_system')->realpath($file_uri);
    \Drupal::logger('yelen_migration')->info("get_existing_file {$file_real_uri}");
    if (!$file_real_uri || !file_exists($file_real_uri)) {
      return NULL;
    }

    // Chercher un fichier déjà enregistré en base
    try {
      $files = \Drupal::entityTypeManager()
        ->getStorage('file')
        ->loadByProperties(['uri' => $file_uri]);
    } catch (\Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException|\Drupal\Component\Plugin\Exception\PluginNotFoundException $e) {
      \Drupal::logger('yelen_migration')->error('Import File @file_uri does not exist: ' . $file_uri . 'Exception: ' . $e->getMessage());
    }

    if ($file = reset($files)) {
      return $file;
    }

    // Si le fichier n'est pas enregistré, on l'ajoute à Drupal
    $file = File::create([
      'uri' => $file_uri,
      'status' => 1,
    ]);
    $file->save();

    return File::load($file->id());
  }

}
