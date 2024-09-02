<?php


namespace Drupal\yelen_faq\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\taxonomy\Entity\Term;

class FaqService
{
  protected EntityTypeManagerInterface $em;

  const FAQ_CONTENT_TYPE_MACHINE_NAME = 'faq_yelen';
  const FAQ_CATEGORIE_MACHINE_NAME = 'faq_categorie';

  public function __construct(EntityTypeManagerInterface $manager){
    $this->em = $manager;
  }

  public function getAllFaq():array {
    $faqs = $this->em->getStorage('node')->loadByProperties(["type"=>'faq_yelen','status'=>true]);
    return $faqs;
  }

  public function getParentCategory(){
      $parentTerms = $this->em
        ->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => self::FAQ_CATEGORIE_MACHINE_NAME]);
        //->loadTree(self::FAQ_CATEGORIE_MACHINE_NAME, 0, 2)

    $parentUniques=[];
    foreach ($parentTerms as $parent){
      $parentUniques[$parent->id()]=[
        'tid'=>$parent->id(),
        'name'=>$parent->label(),
        'description'=>$parent->description->value,
        'image'=> $this->getUri($parent->field_image->entity),
        'parent'=>$parent->parent->getValue()[0]['target_id']
      ];
    }
     /* $parents = [];
      foreach ($parentTerms as $term){
        $parent = Term::load($term->tid);
        $parents[]=[
          'tid'=>$parent->id(),
          'uuid'=>$parent->uuid(),
          'name'=>$parent->label(),
          'description'=>$parent->getDescription(),
          'parent'=>$parent->parent->getValue(),
          //'image'=>
        ]
      }*/
      return $parentUniques;
  }

  public function getFaqsByCategory($category, ?string $sousCategory = null): array {

    $parent_term = $this->em
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $category, 'vid' => self::FAQ_CATEGORIE_MACHINE_NAME]);

    $parent_term = reset($parent_term);
    $parent_term_id = $parent_term->id();

    $faqs = [];

    if ($sousCategory) {
      $child_term = $this->em
        ->getStorage('taxonomy_term')
        ->loadByProperties([
          'name' => $sousCategory,
          'parent' => $parent_term_id,
          'vid' => self::FAQ_CATEGORIE_MACHINE_NAME,
        ]);

      if ($child_term) {
        $child_term = reset($child_term);
        $child_term_id = $child_term->id();

        $faqs = $this->em
          ->getStorage('node')
          ->loadByProperties([
            'type' => self::FAQ_CONTENT_TYPE_MACHINE_NAME,
            'status' => true,
            'field_categorie_faq' => $child_term_id,
          ]);
      }
    } else {
      $child_terms = $this->em
        ->getStorage('taxonomy_term')
        ->loadByProperties(['parent' => $parent_term_id, 'vid' => self::FAQ_CATEGORIE_MACHINE_NAME]);

      $category_and_children_ids = $child_terms ? array_merge([$parent_term_id], array_keys($child_terms)) : [$parent_term_id];

      $faqs = $this->em
        ->getStorage('node')
        ->loadByProperties([
          'type' => self::FAQ_CONTENT_TYPE_MACHINE_NAME,
          'status' => true,
          'field_categorie_faq' => $category_and_children_ids,
        ]);
    }

    $preparedFaqs = [];
    foreach ($faqs as $faq) {
      $preparedFaqs[] = [
        'nid' => $faq->id(),
        'title' => $faq->getTitle(),
        'reponse' => $faq->field_reponse->value,
      ];
    }

    return $preparedFaqs;
  }


  public static function getUri($field_image)
  {
    $request = \Drupal::Request();

    //permet de generer l'url qui mêne à un fichier
    return  $field_image !== null ? \Drupal::service('file_url_generator')->generateAbsoluteString($field_image->getFileUri()) : null;
    //$path = explode('sites', $chemin);
    //return (Settings::get('external_host') !== null && isset($path[1]) && $path[1]!== "" ) ? sprintf("%s/sites%s", substr(Settings::get('external_host'), 0, -1), $path[1]) : null;
  }

}
