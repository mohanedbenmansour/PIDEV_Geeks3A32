<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddProductPageController extends AbstractController
{
    /**
     * @Route("/product/add", name="add_product_page")
     */
    public function index(): Response
    {
        return $this->render('add_product_page/index.html.twig', [
            'controller_name' => 'AddProductPageController',
        ]);
    }
}
