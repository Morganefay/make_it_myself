<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Repository\AvatarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountInfoController extends AbstractController
{

    /**
     * @Route("/mon-compte/modification_de_mes_informations", name="account_info")
     * @return Response
     */
    public function index(): Response
    {


        return $this->render('account/info.html.twig');
    }
}
