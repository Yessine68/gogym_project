<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategorieEvenementRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\CategorieEvenement;

use App\Form\CategorieEvenementType; 


use Symfony\Component\HttpFoundation\Request;

#[Route('/categorieE')]
class CategorieEvenementController extends AbstractController
{

    #[Route('/', name: 'app_categorieE_index', methods: ['GET'])]
    public function index(CategorieEvenementRepository $categorieRepository): Response
    {
        return $this->render('CategorieEvenement/index.html.twig',  [
            'categories' => $categorieRepository->findAll(),
        ]);
        
    }

    #[Route('/new', name: 'app_categorieE_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieEvenementRepository $categorieRepository): Response
    {
        $categorieEvenement = new CategorieEvenement();
        $form = $this->createForm(CategorieEvenementType::class, $categorieEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorieEvenement, true);

            return $this->redirectToRoute('app_categorieE_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CategorieEvenement/new.html.twig', [
            'categorie' => $categorieEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieE_show', methods: ['GET'])]
    public function show(CategorieEvenement $categorieEvenement): Response
    {
        return $this->render('CategorieEvenement/show.html.twig', [
            'categorie' => $categorieEvenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorieE_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieEvenement $categorieEvenement, CategorieEvenementRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieEvenementType::class, $categorieEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorieEvenement, true);

            return $this->redirectToRoute('app_categorieE_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CategorieEvenement/edit.html.twig', [
            'categorie' => $categorieEvenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieE_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieEvenement $categorieEvenement, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieEvenement->getId(), $request->request->get('_token'))) {
            $categorieEvenementRepository->remove($categorieEvenement, true);
        }

        return $this->redirectToRoute('app_categorieE_index', [], Response::HTTP_SEE_OTHER);
    }
}
?>