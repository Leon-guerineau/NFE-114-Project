<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Routing\Attribute\Route;
use DateTime;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

class UserController extends AbstractController
{
  private UserRepository $userRepository;

  public function __construct(Environment $twig, UserRepository $userRepository,private EntityManager $em)
  {
    parent::__construct($twig);
    $this->userRepository = $userRepository;
  }

  #[Route(path: "/user/create", name: 'user_create')]
  public function create()
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
  public function list()
  {
    // récupérer tous les utilisateurs
    $users = $this->userRepository->findAll();

    // Transmettre à la vue la liste des utilisateurs à afficher
    echo $this->twig->render('users/list.html.twig', ['users' => $users]);
  }
}
