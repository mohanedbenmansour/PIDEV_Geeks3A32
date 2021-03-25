<?php

namespace App\Controller;

use App\Entity\Publicite;
use App\Form\PublicityType;
use App\Repository\PubliciteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publicite")
 */
class PubliciteController extends AbstractController
{
    /**
     * @Route("/", name="publicite_index", methods={"GET"})
     */
    public function index(PubliciteRepository $publiciteRepository): Response
    {
        return $this->render('publicite/index.html.twig', [
            'publicites' => $publiciteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="publicite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $publicite = new Publicite();
        $form = $this->createForm(PublicityType::class, $publicite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imagefilename']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $publicite->setImage($newFilename);




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publicite);
            $entityManager->flush();

            return $this->redirectToRoute('publicite_index');
        }

        return $this->render('publicite/new.html.twig', [
            'publicite' => $publicite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publicite_show", methods={"GET"})
     */
    public function show(Publicite $publicite): Response
    {
        return $this->render('publicite/show.html.twig', [
            'publicite' => $publicite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="publicite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Publicite $publicite): Response
    {
        $form = $this->createForm(PublicityType::class, $publicite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imagefilename']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $publicite->setImage($newFilename);



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publicite);
            $entityManager->flush();

            return $this->redirectToRoute('publicite_index');
        }

        return $this->render('publicite/edit.html.twig', [
            'publicite' => $publicite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publicite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Publicite $publicite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($publicite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('publicite_index');
    }
}
