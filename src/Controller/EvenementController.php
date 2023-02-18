<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EvenementRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Evenement;

use App\Form\EvenementType; 

use Symfony\Component\HttpFoundation\Request;

class EvenementController extends AbstractController
{

    #[Route('/Evenement/Read', name: 'Read_Evenement')]
    public function ReadEvenement(EvenementRepository $repo): Response
    {
        $Evenements = $repo->findAll();
        
        return $this->render('Evenement/ReadEvenement.html.twig', ["Evenements"=>$Evenements]);
    }

    #[Route('/Evenement/Create', name: 'Create_Evenement')]
    public function CreateEvenement(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Evenement = new Evenement();
        $form = $this->createForm(EvenementType::class,$Evenement);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Evenement);
            $em->flush();
            return $this->redirectToRoute('Read_Evenement');
        }

        return $this->renderForm('Evenement/CreateEvenement.html.twig',['form'=>$form]);
    
    }

    #[Route('/Evenement/Delete/{id_e}', name: 'Delete_Evenement')]
    public function DeleteEvenement(ManagerRegistry $doctrine, $id_e): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Evenement::class);
        $Evenement= $repo->find($id_e);
        $em->remove($Evenement);
        $em->flush();

        return $this->redirectToRoute('Read_Evenement');
    }

    #[Route('/Evenement/Update/{id_e}', name: 'Update_Evenement')]
    public function UpdateEvenement(ManagerRegistry $doctrine, $id_e, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Evenement = $doctrine->getRepository(Evenement::class)->find($id_e);
        $form = $this->createForm(EvenementType::class,$Evenement);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Evenement);
            $em->flush();
            return $this->redirectToRoute('Read_Evenement');
        }

        return $this->renderForm('Evenement/CreateEvenement.html.twig',['form'=>$form]);

    }

}
?>