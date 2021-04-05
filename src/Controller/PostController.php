<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\PubliciteRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request , PostRepository $postRepository, CommentRepository $commRepository): Response
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
            'user'=>$this->getUser(),
            'form' => $form->createView(),
            'posts' => $postRepository->findAll(),
            'comment' => $commRepository->findAll(),


        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('comment/new.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post ,CommentRepository $commRepository, PostRepository $postRepository, PubliciteRepository $publiciteRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
     * @Route("delete/{id}", name="post_delete")
     */
    public function delete($id, PostRepository $repository)
    {

        $post =$repository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('home_page');

    }

}
