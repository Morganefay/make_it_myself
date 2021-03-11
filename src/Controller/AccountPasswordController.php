<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    //manager de doctrine
    private $entityManager;
    //appel de doctrine dans le constructeur
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-compte/modification_du_mot_de_passe", name="account_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $message = null;

        //récupération de l'utilisateur connecté
        $user = $this->getUser();
        //appel du formulaire
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //recuperation des données du mot de passe saisie
            $old_pwd = $form->get('old_password')->getData();

            //je le compare a celui enregistré en bdd
            if($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);

                //j'enregistre les informations en base
                $user->setPassword($password);
                //execute la persistance.enregistre la data en bdd
                $this->entityManager->flush();

                //redirection du user vers la page compte
                //return $this->redirectToRoute('account');
                $message = "Votre mot de passe a bien été mis a jour !";
            } else {
                $message = "Votre mot de passe actuel n'est pas bon ";
            };

        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'message' => $message
        ]);
    }
}
