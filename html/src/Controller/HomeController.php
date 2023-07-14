<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    // Nombre d'éléments à afficher sur les listes
    const NB_ELEMENT_HOME = 10;

    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository,
        private readonly PostRepository $postRepository,
        private readonly GameRepository $gameRepository
    )
    {
        parent::__construct($twig);
    }

    // Page d'Accueil
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'users' => $this->userRepository->getRecent(self::NB_ELEMENT_HOME),
            'games' => $this->gameRepository->getRecent(self::NB_ELEMENT_HOME),
            'posts' => $this->postRepository->getRecent(self::NB_ELEMENT_HOME),
            'nbUsers' => count($this->userRepository->findAll()) - self::NB_ELEMENT_HOME,
            'nbGames' => count($this->gameRepository->findAll()) - self::NB_ELEMENT_HOME,
            'nbPosts' => count($this->postRepository->findAll()) - self::NB_ELEMENT_HOME,
        ]);
    }
}
