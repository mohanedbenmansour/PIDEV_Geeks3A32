<?php

namespace App\Controller;

use App\Entity\CommentEvent;
use App\Entity\Event;
use App\Entity\Participation;
use App\Form\CommentEventType;
use App\Form\EventType;
use App\Repository\CategorieEventRepository;
use App\Repository\CommentEventRepository;
use App\Repository\EventRepository;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    private static $uid = 4;
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(Request $request,PaginatorInterface $paginator,CategorieEventRepository $categorieEventRepository): Response
    {
        $now = new \DateTime();
        $donnees = $this->getDoctrine()->getManager()
            ->createQuery('SELECT e
                FROM App\Entity\Event e
                WHERE e.dateDebut > :now
                ORDER BY e.dateDebut ASC')
            ->setParameter('now', $now)->getResult();
        $events = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            9);
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'categories' => $categorieEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/event/{id}/deletecom/{idc}", name="commentEvent_delete", methods={"DELETE"})
     */
    public function Commentdelete(Request $request, CommentEvent $comment, EventRepository $eventRepository,$id
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {

            $event=$eventRepository->find($id);
            $event->removeComment($comment);

            $entityManager = $comment->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_show');
    }
    /**
     * @Route("/{id}/addparticipation", name="add_participation", methods={"GET"})
     */
    public function addParticipation(Request $request,EventRepository $eventRepository,$id
    ): Response
    {
        $event=$eventRepository->find($id);
        $nbr=0;
        foreach ($event->getParticipations() as $p){
            if($p->getUser()->getId()==self::$uid){
                $nbr++;
            }
        }
        if($nbr < $event->getNbParticipants()){
            $participation = new Participation();

            $participation->setUser($this->getUser());
            $participation->setDate(new \DateTime());
            $event->addParticipation($participation);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('event_index');
        }
        else return $this->redirectToRoute('event_show');
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $event->setDatePub(new \DateTime());
        $event->setUser($this->getUser());
        $event->setEtat('enabled');

        if ($form->isSubmitted() && $form->isValid()) {

           /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['img']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $event->setImg($newFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET","POST"} )
     */
    public function show(Request $request, Event $event, CommentEventRepository $commentEventRepository): Response
    {
        $complet=false;
        $result=false;
        $nbr=0;
        foreach ($event->getParticipations() as $p){
            if($p->getUser()->getId()==self::$uid){
                $result=true;
                $nbr++;
            }
        }
        if($nbr >= $event->getNbParticipants())
            $complet=true;
        $participants = $this->getDoctrine()->getManager()
            ->createQuery('SELECT p
                FROM App\Entity\Participation p
                WHERE p.event = :e')
            ->setParameter('e', $event)->getResult();
        $suggestedEvents = $this->getDoctrine()->getManager()
            ->createQuery('SELECT se
                FROM App\Entity\Event se
                WHERE se.Category = :c')
            ->setParameter('c', $event->getCategory())->setMaxResults(2)->getResult();
        $comments = $this->getDoctrine()->getManager()
            ->createQuery('SELECT c
                FROM App\Entity\CommentEvent c
                WHERE c.event = :e')
            ->setParameter('e', $event)->getResult();

        $comment = new CommentEvent();
        $form = $this->createForm(CommentEventType::class, $comment);
        $form->handleRequest($request);

        $comment->setDate(new \DateTime());
        $comment->setUser($this->getUser());
        $comment->setEvent($event);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/show.html.twig', [
            'participants' => $participants,
            'event' => $event,
            'exist' => $result,
            'complet' => $complet,
            'comments' => $comments,
            'form' => $form->createView(),
            'suggestedEvents' => $suggestedEvents,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['img']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $event->setImg($newFilename);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $event->setEtat('disabled');
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }



    /**
     * @Route("/dashboard/events", name="back_events")
     */
    public function events_index(EventRepository $eventRepository): Response
    {
        return $this->render('dashboard/events/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/dashboard/events/{id}", name="back_events_delete", requirements={"id":"\d+"},  methods={"DELETE"})
     */
    public function event_delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $event->setEtat('disabled');
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_events');
    }

    /**
     * @Route("/dashboard/events/{id}", name="back_events_show", requirements={"id":"\d+"},  methods={"GET"})
     */
    public function event_show(Event $event): Response
    {
        $complet=false;
        $nbr=0;
        foreach ($event->getParticipations() as $p){
            if($p->getUser()->getId()==self::$uid){
                $result=true;
                $nbr++;
            }
        }
        if($nbr >= $event->getNbParticipants())
            $complet=true;
        $participants = $this->getDoctrine()->getManager()
            ->createQuery('SELECT p
                FROM App\Entity\Participation p
                WHERE p.event = :e')
            ->setParameter('e', $event)->getResult();
        $comments = $this->getDoctrine()->getManager()
            ->createQuery('SELECT c
                FROM App\Entity\CommentEvent c
                WHERE c.event = :e')
            ->setParameter('e', $event)->getResult();
        return $this->render('dashboard/events/show.html.twig', [
            'event' => $event,
            'participants' => $participants,
            'complet' => $complet,
            'comments' => $comments,
        ]);
    }

}
