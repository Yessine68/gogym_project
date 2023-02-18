<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\LivraisonRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Livraison;

use App\Form\LivraisonType; 

use Symfony\Component\HttpFoundation\Request;

class LivraisonController extends AbstractController
{

    #[Route('/Livraison/Read', name: 'Read_Livraison')]
    public function ReadLivraison(LivraisonRepository $repo): Response
    {
        $Livraisons = $repo->findAll();
        
        return $this->render('Livraison/ReadLivraison.html.twig', ["Livraisons"=>$Livraisons]);
    }

    #[Route('/Livraison/Create', name: 'Create_Livraison')]
    public function CreateLivraison(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class,$Livraison);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Livraison);
            $em->flush();
            return $this->redirectToRoute('Read_Livraison');
        }

        return $this->renderForm('Livraison/CreateLivraison.html.twig',['form'=>$form]);
    
    }

    #[Route('/Livraison/Delete/{id_liv}', name: 'Delete_Livraison')]
    public function DeleteLivraison(ManagerRegistry $doctrine, $id_liv): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Livraison::class);
        $Livraison= $repo->find($id_liv);
        $em->remove($Livraison);
        $em->flush();

        return $this->redirectToRoute('Read_Livraison');
    }

    #[Route('/Livraison/Update/{id_liv}', name: 'Update_Livraison')]
    public function UpdateLivraison(ManagerRegistry $doctrine, $id_liv, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Livraison = $doctrine->getRepository(Livraison::class)->find($id_liv);
        $form = $this->createForm(LivraisonType::class,$Livraison);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Livraison);
            $em->flush();
            return $this->redirectToRoute('Read_Livraison');
        }

        return $this->renderForm('Livraison/CreateLivraison.html.twig',['form'=>$form]);

    }

}
?>