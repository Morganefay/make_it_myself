<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/a-propos", name="about")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //on cree le mail
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('marie.collin.dev@gmail.com')
                ->subject('Nouveau message de Make it Myself')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'mail' => $contact->get('email')->getData(),
                    'sujet' => $contact->get('sujet')->getData(),
                    'content' => $contact->get('content')->getData(),
                ]);

             
            //on envoie le mail
            $mailer->send($email);

            //on confirme et on redirige
            $this->addFlash('success', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais');
            return $this->redirectToRoute('about');

        }
        return $this->render('about/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
