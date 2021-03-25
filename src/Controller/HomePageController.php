<?php

namespace App\Controller;

use App\Repository\PubliciteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/homepage", name="home_page")
     */
    public function index(PubliciteRepository $publiciteRepository): Response
    {
        return $this->render('home_page/index.html.twig', [
            'publicities' => $publiciteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/profil", name="profil")
     */
    public function profil(): Response
    {
        return $this->render('home_page/profil.html.twig', [
            'controller_name' => 'profilPageController',
        ]);
    }
}
