<?php

namespace App\Controller;

use App\Entity\Reports;
use App\Form\ReportsType;
use App\Repository\ReportsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reports")
 */
class ReportsController extends AbstractController
{
    /**
     * @Route("/", name="reports_index", methods={"GET"})
     */
    public function index(ReportsRepository $reportsRepository): Response
    {
        return $this->render('reports/index.html.twig', [
            'reports' => $reportsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reports_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $report = new Reports();
        $form = $this->createForm(ReportsType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute('reports_index');
        }

        return $this->render('reports/new.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reports_show", methods={"GET"})
     */
    public function show(Reports $report): Response
    {
        return $this->render('reports/show.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reports_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reports $report): Response
    {
        $form = $this->createForm(ReportsType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reports_index');
        }

        return $this->render('reports/edit.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reports_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reports $report): Response
    {
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($report);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reports_index');
    }
}
