<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

class UserController extends AbstractController
{
  public function __construct(
      Environment $twig,
      private UserRepository $userRepository,
      private EntityManager $em
  )
  {
    parent::__construct($twig);
  }

  #[Route(path: "/user/create", name: 'user_create')]
  public function create(): void
  {
    $user = new User();

    $user
      ->setUsername("Alex Payne")
      ->setPassword(password_hash('test', PASSWORD_BCRYPT))
      ->setEmail("mozefebid@nol.mg")
    ;

    $this->em->persist($user);
    $this->em->flush();
  }

  #[Route(path: "/users/list", name: 'users_list')]
  public function list(): Response
  {
    // récupérer tous les utilisateurs
    $users = $this->userRepository->findAll();

    // Transmettre à la vue la liste des utilisateurs à afficher
    return $this->render('users/list.html.twig', ['users' => $users]);
  }
}
