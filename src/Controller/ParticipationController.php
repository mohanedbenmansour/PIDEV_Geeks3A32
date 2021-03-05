<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipationController extends AbstractController
{
    private static $uid = 1;
    /**
     * @Route("/participation", name="participation")
     */
    public function index(): Response
    {
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }

    /**
     * @Route("/{id}", name="add_participation", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $participation = new Participation();
        
        $participation->setUserId(self::$uid);
        $participation->setType(1);
        $participation->setDate(new \DateTime());
        $participation->setObjectId($request->get('id'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($participation);
        $em->flush();

        return $this->redirectToRoute('event_index');
        
    }
}
