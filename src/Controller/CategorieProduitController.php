<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategorieProduitRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\CategorieProduit;

use App\Form\CategorieProduitType; 

use Symfony\Component\HttpFoundation\Request;

class CategorieProduitController extends AbstractController
{

    #[Route('/CategorieProduit/Read', name: 'Read_CategorieProduit')]
    public function ReadCategorieProduit(CategorieProduitRepository $repo): Response
    {
        $CategorieProduits = $repo->findAll();
        
        return $this->render('CategorieProduit/ReadCategorieProduit.html.twig', ["CategorieProduits"=>$CategorieProduits]);
    }

    #[Route('/CategorieProduit/Create', name: 'Create_CategorieProduit')]
    public function CreateCategorieProduit(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $CategorieProduit = new CategorieProduit();
        $form = $this->createForm(CategorieProduitType::class,$CategorieProduit);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($CategorieProduit);
            $em->flush();
            return $this->redirectToRoute('Read_CategorieProduit');
        }

        return $this->renderForm('CategorieProduit/CreateCategorieProduit.html.twig',['form'=>$form]);
    
    }

    #[Route('/CategorieProduit/Delete/{id_cat_p}', name: 'Delete_CategorieProduit')]
    public function DeleteCategorieProduit(ManagerRegistry $doctrine, $id_cat_p): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(CategorieProduit::class);
        $CategorieProduit= $repo->find($id_cat_p);
        $em->remove($CategorieProduit);
        $em->flush();

        return $this->redirectToRoute('Read_CategorieProduit');
    }

    #[Route('/CategorieProduit/Update/{id_cat_p}', name: 'Update_CategorieProduit')]
    public function UpdateCategorieProduit(ManagerRegistry $doctrine, $id_cat_p, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $CategorieProduit = $doctrine->getRepository(CategorieProduit::class)->find($id_cat_p);
        $form = $this->createForm(CategorieProduitType::class,$CategorieProduit);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($CategorieProduit);
            $em->flush();
            return $this->redirectToRoute('Read_CategorieProduit');
        }

        return $this->renderForm('CategorieProduit/CreateCategorieProduit.html.twig',['form'=>$form]);

    }

}
?>