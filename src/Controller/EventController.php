<?php

namespace App\Controller;

use App\Entity\CategorieEvent;
use App\Entity\Event;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\CategorieEventRepository;
use App\Repository\ParticipationRepository;
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
    private static $uid = 1;
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
            'uid' => self::$uid,
        ]);
    }
    //$eventRepository->findAll()
    /**
     * @Route("/{id}/addparticipation", name="add_participation", methods={"GET"})
     */
    public function addParticipation(Request $request,EventRepository $eventRepository,$id
    ): Response
    {
        $event=$eventRepository->find($id);
        $nbr=0;
        foreach ($event->getParticipations() as $p){
            if($p->getUserId()==self::$uid){
                $nbr++;
            }
        }
        if($nbr < $event->getNbParticipants()){
            $participation = new Participation();

            $participation->setUserId(self::$uid);
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
        $event->setUserId(1);

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
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        $complet=false;
        $result=false;
        $nbr=0;
        foreach ($event->getParticipations() as $p){
            if($p->getUserId()==self::$uid){
                $result=true;
                $nbr++;
            }
        }
        if($nbr >= $event->getNbParticipants())
            $complet=true;
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'uid' => self::$uid,
            'exist' => $result,
            'complet' => $complet,
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
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }


   
}
