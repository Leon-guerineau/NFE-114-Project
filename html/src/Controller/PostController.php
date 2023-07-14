<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/post', name: 'post_')]
class PostController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly PostRepository $postRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/list', name: 'list')]
    public function listPost(): Response
    {
        return $this->render('post/post-list.html.twig', [
            'posts' => $this->postRepository->findAll(),
        ]);
    }

    #[Route('/show/{postId}', name: 'show')]
    public function showPost($params): Response
    {
        if (!$post = $this->postRepository->find($params['postId'])) {
            header('Location: /');
            exit();
        }

        return $this->render('post/post-show.html.twig', [
            'post' => $post
        ]);
    }
}
