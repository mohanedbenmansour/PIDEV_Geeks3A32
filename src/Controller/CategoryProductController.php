<?php

namespace App\Controller;

use App\Entity\CategoryProduct;
use App\Form\CategoryProductType;
use App\Repository\CategoryProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/CategoryProduct")
 */
class CategoryProductController extends AbstractController
{
    /**
     * @Route("/", name="CategoryProduct_index", methods={"GET"})
     */
    public function index(CategoryProductRepository $categoryRepository): Response
    {
        return $this->render('CategoryProduct/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="CategoryProduct_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new CategoryProduct();
        $form = $this->createForm(CategoryProductType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('CategoryProduct_index');
        }

        return $this->render('CategoryProduct/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="CategoryProduct_show", methods={"GET"})
     */
    public function show(CategoryProduct $category): Response
    {
        return $this->render('CategoryProduct/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="CategoryProduct_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoryProduct $category): Response
    {
        $form = $this->createForm(CategoryProductType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('CategoryProduct_index');
        }

        return $this->render('CategoryProduct/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="category_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(CategoryProduct::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("category_index");
    }
}
