<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\LigneCommandeRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\LigneCommande;

use App\Form\LigneCommandeType; 

use Symfony\Component\HttpFoundation\Request;

class LigneCommandeController extends AbstractController
{

    #[Route('/LigneCommande/Read', name: 'Read_LigneCommande')]
    public function ReadLigneCommande(LigneCommandeRepository $repo): Response
    {
        $LigneCommandes = $repo->findAll();
        
        return $this->render('LigneCommande/ReadLigneCommande.html.twig', ["LigneCommandes"=>$LigneCommandes]);
    }

    #[Route('/LigneCommande/Create', name: 'Create_LigneCommande')]
    public function CreateLigneCommande(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $LigneCommande = new LigneCommande();
        $form = $this->createForm(LigneCommandeType::class,$LigneCommande);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($LigneCommande);
            $em->flush();
            return $this->redirectToRoute('Read_LigneCommande');
        }

        return $this->renderForm('LigneCommande/CreateLigneCommande.html.twig',['form'=>$form]);
    
    }

    #[Route('/LigneCommande/Delete/{id_ligne}', name: 'Delete_LigneCommande')]
    public function DeleteLigneCommande(ManagerRegistry $doctrine, $id_ligne): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(LigneCommande::class);
        $LigneCommande= $repo->find($id_ligne);
        $em->remove($LigneCommande);
        $em->flush();

        return $this->redirectToRoute('Read_LigneCommande');
    }

    #[Route('/LigneCommande/Update/{id_ligne}', name: 'Update_LigneCommande')]
    public function UpdateLigneCommande(ManagerRegistry $doctrine, $id_ligne, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $LigneCommande = $doctrine->getRepository(LigneCommande::class)->find($id_ligne);
        $form = $this->createForm(LigneCommandeType::class,$LigneCommande);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($LigneCommande);
            $em->flush();
            return $this->redirectToRoute('Read_LigneCommande');
        }

        return $this->renderForm('LigneCommande/CreateLigneCommande.html.twig',['form'=>$form]);

    }

}
?>