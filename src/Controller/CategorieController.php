<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {    $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('CategorieProduit/index.html.twig',  [
            'categories' => $categorieRepository->findAll(),
            'notifications' => $notifications,
            
        ]);
        
    }

    #[Route('/JSON/getAll', name: 'app_produit_JSON', methods: ['GET'])]
    public function index_JSON(SerializerInterface $serializer,CategorieRepository $categorieRepository): Response
    {
        $reclamations = $reclamationRepository->findAll();
        $json = $serializer->serialize($reclamations, 'json',[
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['reponses'],
        ]);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRepository $categorieRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CategorieProduit/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('CategorieProduit/show.html.twig', [
            'categorie' => $categorie,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->save($categorie, true);

            return $this->redirectToRoute('app_categorie_index', [
                'notifications' => $notifications,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CategorieProduit/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }

        return $this->redirectToRoute('app_categorie_index', [
            'notifications' => $notifications,
        ], Response::HTTP_SEE_OTHER);
    }

}
