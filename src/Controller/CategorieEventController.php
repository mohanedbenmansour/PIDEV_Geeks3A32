<?php


namespace App\Controller;

use App\Entity\CategorieEvent;
use App\Form\CategorieEventType;
use App\Repository\CategorieEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategorieEventController extends AbstractController
{

    /**
     * @Route("/dashboard/categories_event", name="back_categoriesevent")
     */
    public function categoriesEvent_index(CategorieEventRepository $categorieRepository): Response
    {
        return $this->render('dashboard/categorie_event/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/dashboard/categories_event/{id}", name="back_categoriesevent_delete", requirements={"id":"\d+"}, methods={"DELETE"})
     */
    public function categoriesEvent_delete(Request $request, CategorieEvent $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_categoriesevent');
    }

    /**
     * @Route("/dashboard/categories_event/new", name="back_categoriesevent_new", methods={"GET","POST"})
     */
    public function categoriesEvent_new(Request $request): Response
    {
        $category = new CategorieEvent();
        $form = $this->createForm(CategorieEventType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_categoriesevent');
        }

        return $this->render('dashboard/categorie_event/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/categories_event/{id}/edit", name="back_categoriesevent_edit", methods={"GET","POST"})
     */
    public function categoriesEvent_edit(Request $request, CategorieEvent $category): Response
    {
        $form = $this->createForm(CategorieEventType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_categoriesevent');
        }

        return $this->render('dashboard/categorie_event/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }
}