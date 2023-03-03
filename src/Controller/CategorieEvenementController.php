<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategorieEvenementRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\CategorieEvenement;

use App\Form\CategorieEvenementType; 

use Symfony\Component\HttpFoundation\Request;

class CategorieEvenementController extends AbstractController
{

    #[Route('/CategorieEvenement/Read', name: 'Read_CategorieEvenement')]
    public function ReadCategorieEvenement(CategorieEvenementRepository $repo): Response
    {
        $CategorieEvenements = $repo->findAll();
        
        return $this->render('CategorieEvenement/ReadCategorieEvenement.html.twig', ["CategorieEvenements"=>$CategorieEvenements]);
    }

    #[Route('/CategorieEvenement/Create', name: 'Create_CategorieEvenement')]
    public function CreateCategorieEvenement(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $CategorieEvenement = new CategorieEvenement();
        $form = $this->createForm(CategorieEvenementType::class,$CategorieEvenement);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($CategorieEvenement);
            $em->flush();
            return $this->redirectToRoute('Read_CategorieEvenement');
        }

        return $this->renderForm('CategorieEvenement/CreateCategorieEvenement.html.twig',['form'=>$form]);
    
    }

    #[Route('/CategorieEvenement/Delete/{id_cat_e}', name: 'Delete_CategorieEvenement')]
    public function DeleteCategorieEvenement(ManagerRegistry $doctrine, $id_cat_e): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(CategorieEvenement::class);
        $CategorieEvenement= $repo->find($id_cat_e);
        $em->remove($CategorieEvenement);
        $em->flush();

        return $this->redirectToRoute('Read_CategorieEvenement');
    }

    #[Route('/CategorieEvenement/Update/{id_cat_e}', name: 'Update_CategorieEvenement')]
    public function UpdateCategorieEvenement(ManagerRegistry $doctrine, $id_cat_e, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $CategorieEvenement = $doctrine->getRepository(CategorieEvenement::class)->find($id_cat_e);
        $form = $this->createForm(CategorieEvenementType::class,$CategorieEvenement);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($CategorieEvenement);
            $em->flush();
            return $this->redirectToRoute('Read_CategorieEvenement');
        }

        return $this->renderForm('CategorieEvenement/CreateCategorieEvenement.html.twig',['form'=>$form]);

    }

}
?>