<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseHomePageController extends AbstractController
{
    /**
     * @Route("/dashboard", name="base_home_page")
     */
    public function index(): Response
    {
        return $this->render('base_home_page/index.html.twig', [
            'controller_name' => 'BaseHomePageController',
        ]);
    }
}
