<?php

namespace App\Controller;


use App\Classe\Mail;
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

            //envoi de mail pour confirmer l'inscription a l'utilisateur, en appelant notre Class Mail et en utilisant le service tier de MailJet
            $mail = new Mail();
            $content = "Bonjour ".$user->getFullname()."<br/>Bienvenue sur Make it Myself !<br/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ante ipsum, porttitor vel tortor sit amet, molestie dignissim dolor. Phasellus laoreet placerat massa, ut vestibulum tortor aliquam et. Nullam eu odio ut mi euismod rutrum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In sodales orci quam, vitae auctor dolor finibus in. Ut id ornare mauris. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla facilisi. Aliquam euismod sem a quam tincidunt, quis mattis sem malesuada. Aenean auctor diam sed urna mattis fermentum.";
            $mail->send($user->getEmail(), $user->getFullname(), 'Bienvenue sur Make it Myself !', $content);



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
