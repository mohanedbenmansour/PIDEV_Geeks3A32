<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\PubliciteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/homepage", name="home_page",methods={"GET","POST"})
     */
    public function index(PubliciteRepository $publiciteRepository,Request $request , PostRepository $postRepository, CommentRepository $commRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $post->setImage("3.jpg");
            $post->getUploadFile();
            $post->setDate(new \DateTime());
            $post->setNbrvue(0);
            $post->setIdUser($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }


        return $this->render('home_page/index.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'posts' => $postRepository->findAll(),
            'comment' => $commRepository->findAll(),
            'publicities' => $publiciteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/profil", name="profil")
     */
    public function profil(): Response
    {
        return $this->render('home_page/profil.html.twig', [
            'controller_name' => 'profilPageController',
        ]);
    }
}
