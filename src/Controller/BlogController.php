<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Header;
use App\Entity\Post;
use App\Form\SearchType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){

        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/blog", name="blog")
     * @param PostRepository $postRepository
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $posts =$this->entityManager->getRepository(Post::class)->findWithSearch($search);
        }else{
            $posts = $this->entityManager->getRepository(Post::class)->findAll();
        }

        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        // Rendu du template Twig
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
            'headers' => $headers
        ]);
    }
}
