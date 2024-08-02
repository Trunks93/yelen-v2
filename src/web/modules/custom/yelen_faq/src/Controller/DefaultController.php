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
    $faqs = $this->faqService->getAllFaq();
    return [
      '#theme' => 'yelen_faq_page',
      '#content'=> [
        'faqs'=> $faqs
      ],
    ];
  }

}
