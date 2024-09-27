<?php
namespace Drupal\yelen_access_quiz\StackMiddleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class QuizAccessMiddleware implements HttpKernelInterface {

 /* protected $httpKernel;

  public function __construct(HttpKernelInterface $http_kernel) {
    $this->httpKernel = $http_kernel;
  }

  public function handle (Request $request, int $type = self::MAIN_REQUEST, bool $catch = true) {
    //dd($request);
    // Code à exécuter avant le rendu de la page.
    // \Drupal::logger('my_module')->notice('Navigation entre les pages détectée.');

    // Appelle la requête suivante dans la chaîne des middlewares.
    //$response = $this->httpKernel->handle($request, $type, $catch);

    // Code à exécuter après le rendu de la page.
    return new Response();
  }*/
}
