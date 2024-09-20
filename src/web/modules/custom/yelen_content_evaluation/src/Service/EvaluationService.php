<?php

namespace Drupal\yelen_content_evaluation\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;


class EvaluationService
{

  protected EntityTypeManagerInterface $em;

  public function __construct(EntityTypeManagerInterface $manager){
    $this->em = $manager;
  }

  public function getEvaluationOfUser($userid,$nodeId){
    $evaluation = $this->em->getStorage('evaluation')
      ->loadByProperties(['uid'=>$userid,'field_contenu'=>$nodeId,'bundle'=>'simple']);

    return $evaluation;

  }


}
