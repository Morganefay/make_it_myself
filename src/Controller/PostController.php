<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Form\CommentType;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    //manager de doctrine
    private $entityManager;

    //appel de doctrine dans le constructeur
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/post/{slug}", name="post.index")
     * @param Post $post
     * @param Request $request
     * @return Response
     */
    public function index(Post $post, Request $request): Response
    {
        // Création d'un formulaire
        $form = $this->createForm(CommentType::class);

        // On va traiter les données du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On réucpère les données du formulaire
            $comment = $form->getData();

            // On attribut au champ post l'article sur lequel on se trouve et l'utilisateur connecté
            $comment->setPost($post);
            $comment->setUser($this->getUser());

            //on recupere le contenu du champ parentid car il n'est pas mappé
            $parentid = $form->get("parentid")->getData();

            //on va chercher le commenatire corespondant
            if($parentid != null){
                $parent = $this->entityManager->getRepository(Comment::class)->find($parentid);
            }
            //on définit le  parent
            $comment->setParent($parent ?? null);

            // Persistence des données
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            // Ajout d'un message flash
            $this->addFlash('success', 'Votre commentaire a bien été publié.');

            // Redirection pour perdre les données de la requête
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/{slug}/add-comment", name="post.ajax.comment", methods={"POST"})
     * @param Post $post
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function ajaxAddComment(Post $post, Request $request): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment = $form->getData();
            $comment->setPost($post);

            $comment->setUser($this->getUser());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->render('post/_comment.html.twig', [
                'comment' => $comment
            ]);
        }
    }

    /**
     * Permet de liker ou unliker un post
     *
     * @Route("/post/{slug}/like", name="post_like")
     *
     * @param Post $post
     * @param PostLikeRepository $likeRepository
     * @return Response
     */
    public function like(Post $post, PostLikeRepository $likeRepository): Response
    {
        //si l'utilisateur n'est pas connecté renvoi une erreur
        $user = $this->getUser();
        if(!$user) return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        //si l'article est déja aimé je veux le supprimer
        if($post->isLikedByUser($user)){
            $like = $likeRepository->findOneBy([
                'post' => $post,
                'user' => $user
            ]);
            //Je retire de la base
            $this->entityManager->remove($like);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like bien supprimé',
                'likes' => $likeRepository->count(['post'=> $post])
            ], 200);
        }
        //si l'article n'est PAS encore aimé par l'utilisateur
        $like = new PostLike();
        $like->setPost($post)->setUser($user);
        //J'enregistre en base
        $this->entityManager->persist($like);
        $this->entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepository->count(['post'=> $post])
        ], 200);
    }
}
