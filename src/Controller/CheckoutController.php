<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(SessionInterface $session,ProductRepository $productRepository): Response
    {$cart=$session->get("cart");
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
        return $this->render('checkout/index.html.twig', [
            "items"=>$cartWithData,
            "total"=>$total
        ]);

    }

    /**
     * @Route("/newcheckout", name="checkout_new")
     */
    public function add(Request $request,SessionInterface $session,ProductRepository $productRepository): Response
    { $orderDetail="";
    $i=1;
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
            $orderDetail.="product".$i.":".$item["product"]->getName()." ,price".$item["product"]->getPrice()." ,quantity:".$item["quantity"]."\n";
        $i++;
        }

$orderDetail.="totalprice:".$total;



        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
     $order->setOrderDetail($orderDetail);
     $date=new \DateTime();
     $order->setCheckoutDate($date->format('Y-m-d H:i:s'));
     $order->setUserId(1);
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('product_front_index');
        }

        return $this->render('checkout/index.html.twig', [
            'order' => $order,
            "items"=>$cartWithData,
            "total"=>$total,
            'form' => $form->createView(),
        ]);
    }
}
