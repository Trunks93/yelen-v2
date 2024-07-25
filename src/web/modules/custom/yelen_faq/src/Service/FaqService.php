<?php


namespace Drupal\yelen_faq\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class FaqService
{
  protected EntityTypeManagerInterface $em;

  public function __construct(EntityTypeManagerInterface $manager){
    $this->em = $manager;
  }

  public function getAllFaq():array {
    $faqs = $this->em->getStorage('node')->loadByProperties(["type"=>'faq','status'=>true]);
    return $faqs;
  }

}
