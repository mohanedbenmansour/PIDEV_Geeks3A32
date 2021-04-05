<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Participation;
use App\Entity\ParticipationT;
use App\Entity\Utilisateur;
use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Form\TournoibackType;
use App\Repository\EventRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\CategoryRepository;
use App\Repository\TournoiRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * @Route("/tournoi")
 */
class TournoiController extends AbstractController
{
    /**
     * @Route("/", name="tournoi_index", methods={"GET"})
     */
    public function index(TournoiRepository $tournoiRepository,CategoryRepository $catRepo,Request $request)
    {

        $limit = 6;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);

        // On récupère les filtres
        $filters = $request->get("categorys");

        // On récupère les annonces de la page en fonction du filtre
        $tournois = $tournoiRepository->getPaginatedTournois($page, $limit, $filters);

        // On récupère le nombre total d'annonces
        $total = $tournoiRepository->getTotalTournois($filters);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('tournoi/index_content.html.twig',compact('tournois', 'total', 'limit', 'page'))
                ]);
        }

        $categorys = $catRepo->findAll();

        return $this->render('tournoi/index.html.twig',compact('tournois', 'total', 'limit', 'page', 'categorys'));
    }

    /**
     * @Route("/indexback", name="tournoi_indexback", methods={"GET"})
     */
    public function indexback(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/indexback.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
    }





    /**
     * @Route("/new", name="tournoi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tournoi = new Tournoi();

        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $tournoi->setImage($newFilename);




            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('tournoi_index');
        }

        return $this->render('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/index/{id}", name="tournoi_show", methods={"GET"})
     */
    public function show(Tournoi $tournoi): Response
    {
        $test=$tournoi->getLienYoutube();
        $test=strrchr($test,'=');
        $tournoi->setLienYoutube(substr($test,1));
        $participantsT = $this->getDoctrine()->getManager()
            ->createQuery('SELECT p
                FROM App\Entity\ParticipationT p
                WHERE p.tournoi = :t')
            ->setParameter('t', $tournoi)->getResult();

        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
            'participantsT' => $participantsT,

        ]);
    }
    /**
     * @Route("/indexback/{id}/active", name="desactiver", methods={"GET","POST"})
     */
    public function desactiver(Tournoi $tournois, int $id ,TournoiRepository $repository): Response
    {

        $repository = $this->getDoctrine()->getRepository(Tournoi::class);
        $tournois=$repository->find($id);
        $test=$tournois->getActive();
        if($test==1){
        $tournois->setActive(0);
        }
        else {
            $tournois->setActive(1);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('tournoi_showback', [
            'tournoi' => $tournois,
            'id'=>$id,
        ]);

    }




    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}/addparticipant", name="participer", methods={"GET","POST"})
     */

    public function participate(Request $request,tournoi $tournois, $id ,TournoiRepository $tournoiRepository,Session $session): Response
    {

        $tournoi=$tournoiRepository->find($id);
        $nbr=0;
        $this->getUser();
        foreach ($tournoi->getParticipationTs() as $p){

        }
        if($nbr < $tournoi->getNbMax()){
            $participation = new ParticipationT();
            $participation->setUserT($this->getUser());
            $tournoi->getId();
            $participation->setTournoi($tournoi);

            $tournoi->addParticipationT($participation);
            $tournoi->setNbMax($tournoi->getNbMax()-1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('tournoi_show', [
                'tournoi' => $tournoi,
                'id'=>$id,
            ]);
        }
        else return $this->redirectToRoute('tournoi_show');
    }


    /**
     * @Route("/indexback/{id}", name="tournoi_showback", methods={"GET"})
     */
    public function showback(Tournoi $tournoi): Response
    {
        return $this->render('tournoi/showback.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tournoi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tournoi $tournoi): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $tournoi->setImage($newFilename);



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournoi_index');
        }

        return $this->render('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editback", name="tournoi_editback", methods={"GET","POST"})
     */
    public function editback(Request $request, Tournoi $tournoi): Response
    {
        $formback = $this->createForm(TournoibackType::class, $tournoi);
        $formback->handleRequest($request);

        if ($formback->isSubmitted() && $formback->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $formback['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $tournoi->setImage($newFilename);



            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tournoi_indexback');
        }

        return $this->render('tournoi/editback.html.twig', [
            'tournoi' => $tournoi,
            'form' => $formback->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tournoi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tournoi $tournoi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tournoi_index');
    }
    /**
     * @Route("/searchTournoix ", name="searchTournoix")
     */
    public function searchTournoix(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Tournoi::class);
        $requestString=$request->get('searchValue');
        $tournoi = $repository->findTournoiByname($requestString);
        $jsonContent = $Normalizer->normalize($tournoi, 'json',['groups'=>'tournois']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }
}

