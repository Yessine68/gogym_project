<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ProduitRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Produit;

use App\Form\ProduitType; 

use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{

    #[Route('/Produit/Read', name: 'Read_Produit')]
    public function ReadProduit(ProduitRepository $repo): Response
    {
        $Produits = $repo->findAll();
        
        return $this->render('Produit/ReadProduit.html.twig', ["Produits"=>$Produits]);
    }

    #[Route('/Produit/Create', name: 'Create_Produit')]
    public function CreateProduit(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Produit = new Produit();
        $form = $this->createForm(ProduitType::class,$Produit);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Produit);
            $em->flush();
            return $this->redirectToRoute('Read_Produit');
        }

        return $this->renderForm('Produit/CreateProduit.html.twig',['form'=>$form]);
    
    }

    #[Route('/Produit/Delete/{id_p}', name: 'Delete_Produit')]
    public function DeleteProduit(ManagerRegistry $doctrine, $id_p): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Produit::class);
        $Produit= $repo->find($id_p);
        $em->remove($Produit);
        $em->flush();

        return $this->redirectToRoute('Read_Produit');
    }

    #[Route('/Produit/Update/{id_p}', name: 'Update_Produit')]
    public function UpdateProduit(ManagerRegistry $doctrine, $id_p, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Produit = $doctrine->getRepository(Produit::class)->find($id_p);
        $form = $this->createForm(ProduitType::class,$Produit);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Produit);
            $em->flush();
            return $this->redirectToRoute('Read_Produit');
        }

        return $this->renderForm('Produit/CreateProduit.html.twig',['form'=>$form]);

    }

}
?>