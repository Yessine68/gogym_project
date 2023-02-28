<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Form\AbonnementType; 
use App\Repository\AbonnementRepository;

use Doctrine\Persistence\ManagerRegistry;

use Twig\Node\Expression\FilterExpression;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonnementController extends AbstractController
{

    #[Route('/Abonnement/Read_Back', name: 'Read_Back_Abonnement')]
    public function ReadBackAbonnement(AbonnementRepository $repo): Response
    {
        $Abonnements = $repo->findAll();
        
        return $this->render('Abonnement/ReadAbonnementBack.html.twig', ["Abonnements"=>$Abonnements]);
    }

    #[Route('/Abonnement/Read_Front', name: 'Read_Front_Abonnement')]
    public function ReadFrontAbonnement(AbonnementRepository $repo): Response
    {
        $Abonnements = $repo->findAll();
        
        return $this->render('Abonnement/ReadAbonnementFront.html.twig', ["Abonnements"=>$Abonnements]);
    }

    #[Route('/Abonnement/Read_More_Front/{id}', name: 'Read_More_Front_Abonnement')]
    public function ReadMoreFrontAbonnement(AbonnementRepository $repo, $id): Response
    {
        $Abonnements = $repo->findById($id);

        return $this->render('Abonnement/ReadMoreAbonnementFront.html.twig', ["Abonnements"=>$Abonnements]);
        
    }
    #[Route('/Abonnement/Create', name: 'Create_Abonnement')]
    public function CreateAbonnement(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Abonnement = new Abonnement();
        $form = $this->createForm(AbonnementType::class,$Abonnement);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Abonnement);
            $em->flush();

            $this->addFlash('notice','Ajout avec success!');

            return $this->redirectToRoute('Read_Back_Abonnement');
        }

        return $this->renderForm('Abonnement/CreateAbonnement.html.twig',['form'=>$form]);
    
    }

    #[Route('/Abonnement/Delete/{id}', name: 'Delete_Abonnement')]
    public function DeleteAbonnement(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Abonnement::class);
        $Abonnement= $repo->find($id);
        $em->remove($Abonnement);
        $em->flush();
        
        $this->addFlash('notice','Suppression avec success!');

        return $this->redirectToRoute('Read_Back_Abonnement');
    }

    #[Route('/Abonnement/Update/{id}', name: 'Update_Abonnement')]
    public function UpdateAbonnement(ManagerRegistry $doctrine, $id, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Abonnement = $doctrine->getRepository(Abonnement::class)->find($id);
        $form = $this->createForm(AbonnementType::class,$Abonnement);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Abonnement);
            $em->flush();

            $this->addFlash('notice','Modification avec success!');

            return $this->redirectToRoute('Read_Back_Abonnement');
        }

        return $this->renderForm('Abonnement/UpdateAbonnement.html.twig',['form'=>$form]);

    }

    // public function configureFilters(Filters $filters): Filters
    // {
    //     return $filters
    //     ->add('type_a');
    // }
}
?>