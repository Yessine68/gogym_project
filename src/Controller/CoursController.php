<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CoursRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Cours;

use App\Form\CoursType; 

use Symfony\Component\HttpFoundation\Request;

class CoursController extends AbstractController
{

    #[Route('/Cours/Read/Front', name: 'FRead_Cours')]
    public function FReadCours(CoursRepository $repo): Response
    {
        $Cours = $repo->findAll();
        
        return $this->render('Cours/FReadCours.html.twig', ["Cours"=>$Cours]);
    }

    #[Route('/Cours/Read/Back', name: 'BRead_Cours')]
    public function BReadCours(CoursRepository $repo): Response
    {
        $Cours = $repo->findAll();
        
        return $this->render('Cours/BReadCours.html.twig', ["Cours"=>$Cours]);
    }
    
    #[Route('/Cours/Create', name: 'Create_Cours')]
    public function CreateCours(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = new Cours();
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('BRead_Cours');
        }

        return $this->renderForm('Cours/CreateCours.html.twig',['form'=>$form]);
    
    }

    #[Route('/Cours/Delete/{id}', name: 'Delete_Cours')]
    public function DeleteCours(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Cours::class);
        $Cours= $repo->find($id);
        $em->remove($Cours);
        $em->flush();

        return $this->redirectToRoute('BRead_Cours');
    }

    #[Route('/Cours/Update/{id}', name: 'Update_Cours')]
    public function UpdateCours(ManagerRegistry $doctrine, $id, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = $doctrine->getRepository(Cours::class)->find($id);
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('BRead_Cours');
        }

        return $this->renderForm('Cours/UpdateCours.html.twig',['form'=>$form]);

    }

}
?>