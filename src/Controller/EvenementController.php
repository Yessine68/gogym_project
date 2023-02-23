<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EvenementRepository;
use App\Repository\CategorieEvenementRepository;


use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Evenement;
use App\Entity\CategorieEvenement;
use App\Form\EvenementType; 

use Symfony\Component\HttpFoundation\Request;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        return $this->render('evenement/afficher.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
    
    #[Route('/afficher', name: 'app_evenement_afficher', methods: ['GET'])]
    public function afficher(EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        return $this->render('evenement/afficher.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
    #[Route('/front', name: 'app_evenement_afficher_front', methods: ['GET'])]
    public function afficherfront(Request $request, EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        return $this->render('evenement/showfront.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
  
    #[Route('/blog/{id}', name: 'app_evenement_afficher_blog', methods: ['GET'])]
    public function afficherblog(Evenement $evenement): Response
    {
        return $this->render('evenement/showblog.html.twig', [
            'event' => $evenement,
            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
    



    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm( EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_afficher', [], Response::HTTP_SEE_OTHER);
    }

}
?>