<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Tournoi;
use App\Form\TournoiType;
use App\Repository\CategoryRepository;
use App\Repository\TournoiRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/tournoi")
 */
class TournoiController extends AbstractController
{
    /**
     * @Route("/", name="tournoi_index", methods={"GET"})
     */
    public function index(TournoiRepository $tournoiRepository): Response
    {
        return $this->render('tournoi/index.html.twig', [
            'tournois' => $tournoiRepository->findAll(),
        ]);
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

        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }
    /**
     * @Route("/{id}", name="participer", methods={"GET","POST"})
     */
    public function participate(Tournoi $tournoi, int $id): Response
    {

        $repository = $this->getDoctrine()->getRepository(Tournoi::class);
        $tournoi=$repository->find($id);
        $tournoi->setNbMax($tournoi->getNbMax()-1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('tournoi/show.html.twig', [
            'tournoi' => $tournoi,
            'id'=>$id,
        ]);
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
