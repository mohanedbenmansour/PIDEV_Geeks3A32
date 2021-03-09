<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Product;
use App\Entity\Category;

use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use phpDocumentor\Reflection\Types\ClassString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/product")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    /**
     * @Route("/shop", name="product_front_index", methods={"GET"})
     */
    public function indexShop(ProductRepository $productRepository): Response
    {
        /*return $this->render('product/index.front.html.twig', [
            'products' => $productRepository->findAll(),
        ]);*/
        $p=$productRepository->findAll();
        dd()    ;
    }
    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request,CategoryRepository $categoryRepository): Response
    {
        $product = new Product();
        $categories= $categoryRepository->findAll();
        $form = $this->createForm(ProductType::class, $product);
       /* $form ->add('category', ChoiceType::class,array(
            "choices"=>array(

            )
        ));*/

        $form->handleRequest($request);

        $category = new Category();
        $category->setName('Computer Peripherals');
        $product->setCategory($category);



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            /** @var UploadedFile $uploadedFile */
 $uploadedFiles = $form['imagefilename']->getData();
 $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
 foreach ($uploadedFiles as $uploadedFile){
     $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
     $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
     $uploadedFile->move(
         $destination,
         $newFilename
     );
     $image=new Images();
     $image->setImageName($newFilename);
     $entityManager->persist($image);

     $product->addImage($image);
 }


            $product->setImage("0");


            $entityManager->persist($category);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    /**
     * @Route("/{id}", name="product_show_front", methods={"GET"})
     */
    public function showFront(Product $product): Response
    {
        return $this->render('product/show.front.html.twig', [
            'product' => $product,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
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
            $product->setImage($newFilename);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="product_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("product_index");
    }
}
