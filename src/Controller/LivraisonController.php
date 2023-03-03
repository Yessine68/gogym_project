<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivraisonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livreur;
use App\Entity\Livraison;
use App\Form\LivraisonType; 
use Symfony\Component\HttpFoundation\Request;

class LivraisonController extends AbstractController
{

    /**
     * @Route("/livraison", name="livraison")
     */
    public function index(): Response
    {
        $livraison= $this->getDoctrine()
            ->getRepository(Livraison::class)->findAll();
        return $this->render('livraison/index.html.twig',
            array("tablivraison"=>$livraison));
    }

    /**
     * @Route("/Ajout_livraison", name="livraison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('livraison', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livraison/Ajout-livraison.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/livraison/supprimer/{id}",name="supprimerLivraison")
     */
    /*public function supprimerLivraison(LivraisonRepository $c,$id,EntityManagerInterface $em)
    {
        $emp= $c->find($id);
        $em->remove($emp);
        $em->flush();
        return $this->redirectToRoute("livraison");
    }*/

    public function supprimerLivraison(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Livraison::class);
        $Livraison= $repo->find($id);
        $em->remove($Livraison);
        $em->flush();

        return $this->redirectToRoute("livraison");
    }





    /*#[Route('/Livraison/Read', name: 'Read_Livraison')]
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

    }*/

}
?>