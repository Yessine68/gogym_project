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

    #[Route('/Cours/Read', name: 'Read_Cours')]
    public function ReadCours(CoursRepository $repo): Response
    {
        $Cours = $repo->findAll();
        
        return $this->render('Cours/ReadCours.html.twig', ["Cours"=>$Cours]);
    }

    #[Route('/Cours/Create', name: 'Create_Cours')]
    public function CreateCours(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = new Cours();
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('Read_Cours');
        }

        return $this->renderForm('Cours/CreateCours.html.twig',['form'=>$form]);
    
    }

    #[Route('/Cours/Delete/{id_cours}', name: 'Delete_Cours')]
    public function DeleteCours(ManagerRegistry $doctrine, $id_cours): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Cours::class);
        $Cours= $repo->find($id_cours);
        $em->remove($Cours);
        $em->flush();

        return $this->redirectToRoute('Read_Cours');
    }

    #[Route('/Cours/Update/{id_cours}', name: 'Update_Cours')]
    public function UpdateCours(ManagerRegistry $doctrine, $id_cours, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = $doctrine->getRepository(Cours::class)->find($id_cours);
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('Read_Cours');
        }

        return $this->renderForm('Cours/CreateCours.html.twig',['form'=>$form]);

    }

}
?>