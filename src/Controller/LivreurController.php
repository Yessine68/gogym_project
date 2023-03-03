<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Livreur;
use App\Form\LivreurSearchType;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LivreurController extends AbstractController
{
    /**
     * @Route("/livreur", name="livreur")
     */
    public function index(LivreurRepository $livreurRepository): Response
    {
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Ajout", name="livreur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livreur);
            $entityManager->flush();

            return $this->redirectToRoute('livreur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/ajout.html.twig', [
            'livreur' => $livreur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/updateLivreur/{id}",name="modifierLivreur")
     */
    public function update(Request $request,$id)
    {
        $table= $this->getDoctrine()
            ->getRepository(Livreur::class)->find($id);
        $form= $this->createForm(LivreurType::class,$table);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("livreur", [],Response::HTTP_SEE_OTHER);
        }
        return $this->render("livreur/edit.html.twig",array("form"=>$form->createView()));
    }
    /**
     * @Route("/livreur/supprimer/{id}",name="supprimerLiv")
     */
    public function supprimer(LivreurRepository $c,$id,EntityManagerInterface $em)
    {
        $emp= $c->find($id);
        $em->remove($emp);
        $em->flush();
        return $this->redirectToRoute("livreur");
    }

    









    /*#[Route('/Livreur/Read', name: 'Read_Livreur')]
    public function ReadLivreur(LivreurRepository $repo): Response
    {
        $Livreurs = $repo->findAll();
        
        return $this->render('Livreur/ReadLivreur.html.twig', ["Livreurs"=>$Livreurs]);
    }

    #[Route('/Livreur/Create', name: 'Create_Livreur')]
    public function CreateLivreur(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Livreur = new Livreur();
        $form = $this->createForm(LivreurType::class,$Livreur);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Livreur);
            $em->flush();
            return $this->redirectToRoute('Read_Livreur');
        }

        return $this->renderForm('Livreur/CreateLivreur.html.twig',['form'=>$form]);
    
    }

    #[Route('/Livreur/Delete/{id_livreur}', name: 'Delete_Livreur')]
    public function DeleteLivreur(ManagerRegistry $doctrine, $id_livreur): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Livreur::class);
        $Livreur= $repo->find($id_livreur);
        $em->remove($Livreur);
        $em->flush();

        return $this->redirectToRoute('Read_Livreur');
    }

    #[Route('/Livreur/Update/{id_livreur}', name: 'Update_Livreur')]
    public function UpdateLivreur(ManagerRegistry $doctrine, $id_livreur, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Livreur = $doctrine->getRepository(Livreur::class)->find($id_livreur);
        $form = $this->createForm(LivreurType::class,$Livreur);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Livreur);
            $em->flush();
            return $this->redirectToRoute('Read_Livreur');
        }

        return $this->renderForm('Livreur/CreateLivreur.html.twig',['form'=>$form]);

    }*/
    
}
