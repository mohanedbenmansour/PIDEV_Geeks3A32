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
            'posts' => $postRepository->findBy(array('etat'=>'enable')),
        ]);
    }



    /**
     * @Route("/adminPost", name="post_admin", methods={"GET"})
     */
    public function indexAdmin(PostRepository $postRepository): Response
    {
        return $this->render('post/Admin.html.twig', [
            'posts' => $postRepository->findAll(),
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
        $post->setEtat('disable');
        $entityManager->flush();
        return $this->redirectToRoute('home_page');

    }

    /**
     * @Route("/disable/{id}", name="disable", methods={"GET"})
     */
    public function disablePost($id,\Swift_Mailer $mailer): Response
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $post->setEtat('disable');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        $message = (new \Swift_Message('Post Disabled'))
            ->setFrom('mohamedamine.zaafouri@esprit.tn')
            ->setTo($post->getIdUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'post/emailsDisable.html.twig',
                    ['post' => $post ]
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirectToRoute('post_admin');

    }


}
