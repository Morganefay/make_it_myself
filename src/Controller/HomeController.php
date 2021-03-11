<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([],["createdAt" => 'DESC'],3);

        // Rendu du template Twig
        return $this->render('home/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
