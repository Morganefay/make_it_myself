<?php

namespace App\Controller;


use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    //manager de doctrine
    private $entityManager;

    //appel de doctrine dans le constructeur
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        //j'instancie mon formulaire : createForm prend deux parametres
        // => la Class de mon Formulaire et l'objet
        $form = $this->createForm(RegisterType::class );
        //ecoute la requete
        $form->handleRequest($request);

        //verifie si le formulaire a été soumis et est valide
        if($form->isSubmitted() && $form->isValid()) {

            //si ok-> injecte dans l objet user toutes les données récupérées du form
            $user = $form->getData();


            //j'enregistre les informations en base
            //fige la data
            $this->entityManager->persist($user);
            //execute la persistance.enregistre la data en bdd
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre inscription est terminée. Vous pouvez dès a présent vous connecter à votre compte !');

            //redirection du user vers la page connexion
            return $this->redirectToRoute('app_login');

        }

        //je passe le formulaire en variable a mon template
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
