<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/game', name: 'game_')]
class GameController extends AbstractController
{
    const NB_ELEMENT_LIST = 25;

    public function __construct(
        Environment                     $twig,
        private readonly GameRepository $gameRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/list', name: 'list')]
    public function games(): Response
    {
        return $this->render('game/game-list.html.twig', [
            'games' => $this->gameRepository->findAll(),
        ]);
    }

    #[Route('/show/{gameId}', name: 'show')]
    public function showPost($params): Response
    {
       if (!$game = $this->gameRepository->find($params['gameId'])) {
           header('Location: /');
           exit();
       }

        return $this->render('game/game-show.html.twig', [
            'game' => $game,
        ]);
    }
}
