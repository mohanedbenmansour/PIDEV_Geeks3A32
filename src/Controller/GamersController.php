<?php

namespace App\Controller;

use App\Entity\Gamers;
use App\Form\GamersType;
use App\Repository\GamersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/gamers")
 * @IsGranted("ROLE_ADMIN")
 */
class GamersController extends AbstractController
{
    /**
     * @Route("/", name="gamers_index", methods={"GET"})
     */
    public function index(GamersRepository $gamersRepository): Response
    {
        return $this->render('gamers/index.html.twig', [
            'gamers' => $gamersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gamers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamer = new Gamers();
        $form = $this->createForm(GamersType::class, $gamer);
        $form->add('playerFile', FileType::class, [
            'mapped' => false
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //photo de profil

            /** @var UploadedFile $uploadedFile */
 $uploadedFile = $form['playerFile']->getData();
 $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
 $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
 $newPlFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
 $uploadedFile->move(
     $destination,
     $newPlFilename
 );
 $gamer->setPlayerPhoto($newPlFilename);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gamer);
            $entityManager->flush();

            return $this->redirectToRoute('gamers_index');
        }

        return $this->render('gamers/new.html.twig', [
            'gamer' => $gamer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gamers_show", methods={"GET"})
     */
    public function show(Gamers $gamer): Response
    {
        return $this->render('gamers/show.html.twig', [
            'gamer' => $gamer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gamers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gamers $gamer): Response
    {
        $form = $this->createForm(GamersType::class, $gamer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamers_index');
        }

        return $this->render('gamers/edit.html.twig', [
            'gamer' => $gamer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gamers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gamers $gamer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gamer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gamer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gamers_index');
    }
}
