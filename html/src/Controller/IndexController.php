<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
//    #[Route(path: "/", name: 'home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route(path: "/contact/{id}", name: 'contact')]
    public function contact($params): Response
    {
        echo $params['id'];
        return $this->render('contact.html.twig');
    }
}
