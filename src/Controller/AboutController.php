<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/a-propos", name="about")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais');

            //TODO gerer envoi de mail
            //dd($form->getData());
        }
        return $this->render('about/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
