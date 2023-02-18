<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CommandeRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Commande;

use App\Form\CommandeType; 

use Symfony\Component\HttpFoundation\Request;

class CommandeController extends AbstractController
{

    #[Route('/Commande/Read', name: 'Read_Commande')]
    public function ReadCommande(CommandeRepository $repo): Response
    {
        $Commandes = $repo->findAll();
        
        return $this->render('Commande/ReadCommande.html.twig', ["Commandes"=>$Commandes]);
    }

    #[Route('/Commande/Create', name: 'Create_Commande')]
    public function CreateCommande(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Commande = new Commande();
        $form = $this->createForm(CommandeType::class,$Commande);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Commande);
            $em->flush();
            return $this->redirectToRoute('Read_Commande');
        }

        return $this->renderForm('Commande/CreateCommande.html.twig',['form'=>$form]);
    
    }

    #[Route('/Commande/Delete/{id_com}', name: 'Delete_Commande')]
    public function DeleteCommande(ManagerRegistry $doctrine, $id_com): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Commande::class);
        $Commande= $repo->find($id_com);
        $em->remove($Commande);
        $em->flush();

        return $this->redirectToRoute('Read_Commande');
    }

    #[Route('/Commande/Update/{id_com}', name: 'Update_Commande')]
    public function UpdateCommande(ManagerRegistry $doctrine, $id_com, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Commande = $doctrine->getRepository(Commande::class)->find($id_com);
        $form = $this->createForm(CommandeType::class,$Commande);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Commande);
            $em->flush();
            return $this->redirectToRoute('Read_Commande');
        }

        return $this->renderForm('Commande/CreateCommande.html.twig',['form'=>$form]);

    }

}
?>