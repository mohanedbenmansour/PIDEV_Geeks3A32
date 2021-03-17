<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LogInController extends AbstractController
{
    /**
     * @Route("/", name="log_in")
     */
    public function index(): Response
    {
        return $this->render('log_in/index.html.twig', [
            'controller_name' => 'LogInController',
        ]);
    }
    /**
     * @Route("/create-checkout-session", name="checkout_stripe")
     */
    public function checkout(SessionInterface $session,ProductRepository $productRepository)
    {  $cart=$session->get("cart");
        $cartWithData=[];
        foreach ($cart as $id=>$quantity){
            $cartWithData[]=[
                "product"=>$productRepository->find($id),
                "quantity"=>$quantity
            ];
        } $total=0;
        foreach ($cartWithData as $item){
            $total+=$item["product"]->getPrice()*$item["quantity"];
        }

        \Stripe\Stripe::setApiKey('sk_test_51ITY45CaVpIIebxidvvfdjLT6YzXvOg6ooq6p5qa2xaMOQqYJZWv5lEYkbXtYuYO1iiG1lx8dw988ltL8uya911C00v1oCvuq5');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "GEEKS",
                    ],
                    'unit_amount' => $total*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl("successPayment",[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl("errorPayment",[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new JsonResponse([ 'id' => $session->id ]) ;

    }
}
