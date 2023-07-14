<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{

    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/list', name: 'list')]
    public function users(): Response
    {
        return $this->render('user/user-list.html.twig',[
            'users' => $this->userRepository->findAll(),
        ]);
    }
}
