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


class GamersController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/gamers", name="gamers_index", methods={"GET"})
     */
    public function index(GamersRepository $gamersRepository): Response
    {
        return $this->render('gamers/index.html.twig', [
            'gamers' => $gamersRepository->findAll(),
        ]);
    }
    /**
     * @Route("/GamersProfiles", name="gamers_list", methods={"GET"})
     */
    public function listGamers(GamersRepository $gamersRepository): Response
    {
        return $this->render('gamers/listGamers.html.twig', [
            'gamers' => $gamersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/GamersProfiles/{id}", name="gamers_list_show", methods={"GET"})
     */
    public function showGamerProfil(Gamers $gamer): Response
    {
        return $this->render('gamers/showGamerProfil.html.twig', [
            'gamer' => $gamer,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/gamers/new", name="gamers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gamer = new Gamers();
        $form = $this->createForm(GamersType::class, $gamer);
        $form->add('playerPhoto', FileType::class, [
            'mapped' => false
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //photo de profil

            /** @var UploadedFile $uploadedFile */
 $uploadedFile = $form['playerPhoto']->getData();
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
     * @Route("/gamers/{id}", name="gamers_show", methods={"GET"})
     */
    public function show(Gamers $gamer): Response
    {
        return $this->render('gamers/show.html.twig', [
            'gamer' => $gamer,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/gamers/{id}/edit", name="gamers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gamers $gamer): Response
    {
        $form = $this->createForm(GamersType::class, $gamer);
        $form->add('playerPhoto', FileType::class, [
            'mapped' => false
        ]);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['playerPhoto']->getData();
 $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
 $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
 $newPlFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
 $uploadedFile->move(
     $destination,
     $newPlFilename
 );
 $gamer->setPlayerPhoto($newPlFilename);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gamers_index');
        }

        return $this->render('gamers/edit.html.twig', [
            'gamer' => $gamer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/gamers/{id}", name="gamers_delete", methods={"DELETE"})
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
