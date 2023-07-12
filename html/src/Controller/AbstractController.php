<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
  protected Environment $twig;

  public function __construct(Environment $twig)
  {
    $this->twig = $twig;
  }

    /**
     * Renders a twig template with its parameters
     *
     * @param string $templatePath
     * @param array $params
     *
     * @return void
     */
    public function render(string $templatePath, array $params = [], Response $response = null): Response
    {
        // Load template
        try {
            $template = $this->twig->load($templatePath);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            // TODO: Flash Error
            exit;
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($template->render($params));

        return $response;
    }
}
