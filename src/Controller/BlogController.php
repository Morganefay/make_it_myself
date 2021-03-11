<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        // Rendu du template Twig
        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
