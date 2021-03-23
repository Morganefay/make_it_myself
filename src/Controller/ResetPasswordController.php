<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/reset/password", name="reset_password")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        //si l'user est déja connecté il est redirigé vers la HomePage
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }
        //si l'email est envoyé je vérifie si il existe en base
        if($request->get('email')){

            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            //si l'user existe
            if($user){
                // Enregistrer en base la demande de reset_password avec user, token , createdAt.
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // Envoyer un mail à l'utlisateur avec un lien lui permettant de mettre a jour son mat de passe.
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);
                $content = 'Bonjour '.$user->getFirstname().'<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site de Make it Myself !<br/><br/>';
                $content .="Merci de bien vouloir cliquer sur le lien suivant pour <a href='".$url."'>mettre à jour votre mot de passe.<a/>";
                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Réinitialiser votre mot de passe sur Make it Myself !' , $content);

                $this->addFlash('success', 'Un mail vient de vous être envoyé pour changer votre mot de passe');

            }else{
                $this->addFlash('success', 'Cette adresse email est inconnue');

            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/reset/password/{token}", name="update_password")
     * @param Request $request
     * @param $token
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function update(Request $request, $token ,UserPasswordEncoderInterface $encoder): Response
    {
        //récupère le token
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        //si il n'existe pas je redirige
        if(!$reset_password){
            return $this->redirectToRoute('reset_password');
        }
        //verifier si le token a expiré
        $now = new \DateTime();
        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {

            $this->addFlash('success', 'Votre de demande de changement de mot de passe a éxpiré. Merci de la renouveller');
            return $this->redirectToRoute('reset_password');
        }
        //Rendre une vue avec mot de passe et confirmer mon mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $new_pwd = $form->get('new_password')->getData();

            //TODO sortir la logique d'encodage mdp avec EventSubscriber
            //Encodage des mdp
            $password = $encoder->encodePassword($reset_password->getUser(), $new_pwd);
            $reset_password->getUser()->setPassword($password);

            //Flush en BDD
            $this->entityManager->flush();

            //Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('success', 'Votre mot de passe a bien été mis a jour');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
