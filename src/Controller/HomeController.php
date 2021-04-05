<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{   private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findBy([],["createdAt" => 'DESC'],3);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        // Rendu du template Twig
        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'headers' => $headers
        ]);
    }
}
