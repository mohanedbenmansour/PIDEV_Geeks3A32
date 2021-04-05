<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    function filterwords($text){
        $filterWords = array('fuck','pute','bitch');
        $filterCount = sizeof($filterWords);
        for ($i = 0; $i < $filterCount; $i++) {
            $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
        }
        return $text;
    }

    /**
     * @Route("/new/{id}", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request , Post $post,CommentRepository $commentRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post->setNbrvue($post->getNbrvue()+1);
        $entityManager->persist($post);
        $entityManager->flush();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setContenue($this->filterwords($comment->getContenue()));

            $comment->setIdpost($post);
            $comment->setIdUser($this->getUser());
            $comment->setDate(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('comment_new', ['id' => $post->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'post' => $post,
            'user'=>$this->getUser(),
            'form' => $form->createView(),
            'comments' => $commentRepository->findBy(array('idpost'=>$post->getId()))
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/{id}/{idp}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,$idp, Comment $comment,CommentRepository $commentRepository,PostRepository $postRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_new', ['id' => $idp]);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'post' => $postRepository->find($idp),
            'form' => $form->createView(),
            'comments' => $commentRepository->findBy(array('idpost'=>$idp))
        ]);
    }

    /**
     * @Route("delete/{id}/{idp}", name="comment_delete")
     */
    public function delete($id,$idp, CommentRepository $repository)
    {

        $comment =$repository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirectToRoute('comment_new', ['id' => $idp]);

    }

}
