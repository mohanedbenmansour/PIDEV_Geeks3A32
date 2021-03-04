<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session,ProductRepository $productRepository): Response
    {
        $cart=$session->get("cart");
        $cartWithData=[];
        foreach ($cart as $id=>$quantity){
            $cartWithData[]=[
                "product"=>$productRepository->find($id),
                "quantity"=>$quantity
            ];
        }
$total=0;
        foreach ($cartWithData as $item){
            $total+=$item["product"]->getPrice()*$item["quantity"];
        }
        return $this->render('cart/index.html.twig', [
           "items"=>$cartWithData,
            "total"=>$total
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session)
    {

       $cart=$session->get('cart',[]);

       if(!empty($cart[$id])){
           $cart[$id]++;

       }else {
           $cart[$id]=1;

       }


       $session->set("cart",$cart);
        return $this->redirectToRoute('cart');    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {

        $cart=$session->get('cart',[]);

        if(!empty($cart[$id])){
           unset($cart[$id]);

        }
        $session->set("cart",$cart);

        return $this->redirectToRoute('cart');
    }
}
