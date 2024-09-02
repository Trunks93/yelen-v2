<?php


namespace Drupal\yelen_faq\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\yelen_faq\Service\FaqService;


class DefaultController extends ControllerBase
{
  protected $faqService;

  public function __construct(FaqService $service)
  {
    $this->faqService = $service;
  }


  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('yelen_faq.service'),
    );
  }


  public function index(): array
  {
    $parents = $this->faqService->getParentCategory();
    //dd($parents);
    return [
      '#theme' => 'yelen_faq_page',
      '#content'=> [
        'parents'=>$parents
      ],
    ];
  }

  public function getFaqGroupBy(string $category, string $sousCategory=null): array
  {
    $faqs = $this->faqService->getFaqsByCategory($category,$sousCategory);
    $parents = $this->faqService->getParentCategory();
    return [
      '#theme' => 'yelen_faq_page',
      '#content'=> [
        'faqs'=> $faqs,
        'parents'=>$parents,
        'category'=>$category,
        'sous_category'=>$sousCategory
      ],
    ];
  }

}