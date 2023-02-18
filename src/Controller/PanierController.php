<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\PanierRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Panier;

use App\Form\PanierType; 

use Symfony\Component\HttpFoundation\Request;

class PanierController extends AbstractController
{

    #[Route('/Panier/Read', name: 'Read_Panier')]
    public function ReadPanier(PanierRepository $repo): Response
    {
        $Paniers = $repo->findAll();
        
        return $this->render('Panier/ReadPanier.html.twig', ["Paniers"=>$Paniers]);
    }

    #[Route('/Panier/Create', name: 'Create_Panier')]
    public function CreatePanier(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Panier = new Panier();
        $form = $this->createForm(PanierType::class,$Panier);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Panier);
            $em->flush();
            return $this->redirectToRoute('Read_Panier');
        }

        return $this->renderForm('Panier/CreatePanier.html.twig',['form'=>$form]);
    
    }

    #[Route('/Panier/Delete/{id_panier}', name: 'Delete_Panier')]
    public function DeletePanier(ManagerRegistry $doctrine, $id_panier): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Panier::class);
        $Panier= $repo->find($id_panier);
        $em->remove($Panier);
        $em->flush();

        return $this->redirectToRoute('Read_Panier');
    }

    #[Route('/Panier/Update/{id_panier}', name: 'Update_Panier')]
    public function UpdatePanier(ManagerRegistry $doctrine, $id_panier, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Panier = $doctrine->getRepository(Panier::class)->find($id_panier);
        $form = $this->createForm(PanierType::class,$Panier);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Panier);
            $em->flush();
            return $this->redirectToRoute('Read_Panier');
        }

        return $this->renderForm('Panier/CreatePanier.html.twig',['form'=>$form]);

    }

}
?>