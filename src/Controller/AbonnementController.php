<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Abonnement;
use App\Form\AbonnementType; 

use App\Repository\AbonnementRepository;

use Doctrine\Persistence\ManagerRegistry;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonnementController extends AbstractController
{

    #[Route('/Abonnement/Read_Back', name: 'Read_Back_Abonnement')]
    public function ReadBackAbonnement(AbonnementRepository $repo, Request $req, ManagerRegistry $doctrine): Response
    {
        $Abonnements = $repo->findAll();
        
        $query = $req->query->get('q');

        $Abonnements = $doctrine->getRepository(Abonnement::class)->findByNomOrType($query);
        
        return $this->render('Abonnement/ReadAbonnementBack.html.twig', ["Abonnements"=>$Abonnements, "query"=>$query]);
    }

    #[Route('/Abonnement/Read_Front', name: 'Read_Front_Abonnement')]
    public function ReadFrontAbonnement(AbonnementRepository $repo, PaginatorInterface $paginator, Request $req, ManagerRegistry $doctrine): Response
    {
        $data = $repo->findAll();

        $Abonnements = $repo->findAll();

        $query = $req->query->get('q');

        $AbonnementsR = $doctrine->getRepository(Abonnement::class)->findByNomOrType($query);

        $Abonnements = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );

        return $this->render('Abonnement/ReadAbonnementFront.html.twig', ["Abonnements"=>$Abonnements, "AbonnementsR"=>$AbonnementsR, "query"=>$query]);
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

    #[Route('/Abonnement/generate-pdf', name: 'abonnement_generate_pdf')]
    public function generatePdfAbonnement(AbonnementRepository $repo, Pdf $pdf)
    {

        $Abonnements = $repo->findAll();

        $html = $this->renderView('abonnement/pdf.html.twig', [
            'Abonnements' => $Abonnements,
        ]);
        
        $pdfContent = $pdf->getOutputFromHtml($html);

        return new Response(
            $pdfContent,
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="abonnement.pdf"',
            )
        );

    }

    #[Route('/Abonnement/Find', name: 'Find_Abonnement')]
    public function FindAbonnement(Request $req, ManagerRegistry $doctrine): Response
    {
        $query = $req->query->get('q');

        $Abonnements = $doctrine->getRepository(Abonnement::class)->findByNomOrType($query);

        return $this->render('Abonnement/search.html.twig', ["Abonnements"=>$Abonnements, "query"=>$query]);
    }

    #[Route('/Abonnement/FindFront', name: 'Find_Abonnement_Front')]
    public function FindAbonnementFront(ManagerRegistry $doctrine, AbonnementRepository $repo, Request $req, PaginatorInterface $paginator): Response
    {
        $data = $repo->findAll();

        $query = $req->query->get('q');

        $AbonnementsR = $doctrine->getRepository(Abonnement::class)->findByNomOrType($query);

        $Abonnements = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );

        return $this->render('Abonnement/searchFront.html.twig', ["Abonnements"=>$Abonnements, "AbonnementsR"=>$AbonnementsR, "query"=>$query]);
    }
    
    #[Route('/Abonnement/sortByAscPrix', name: 'sortByAscPrix_Abonnement')]
    public function sortByAscPrixAbonnement(AbonnementRepository $repo, Request $req): Response
    {
        $query = $req->query->get('q');

        $sortByAscPrixAbonnement = $repo->sortByAscPrix();

        return $this->render('Abonnement/ReadAbonnementBack.html.twig', [
            'Abonnements' => $sortByAscPrixAbonnement, "query"=>$query
        ]);
    }

    #[Route('/Abonnement/sortByDescPrix', name: 'sortByDescPrix_Abonnement')]
    public function sortByDescPrixAbonnement(AbonnementRepository $repo, Request $req): Response
    {
        $query = $req->query->get('q');

        $sortByDescPrixAbonnement = $repo->sortByDescPrix();

        return $this->render('Abonnement/ReadAbonnementBack.html.twig', [
            'Abonnements' => $sortByDescPrixAbonnement, "query"=>$query
        ]);
    }

}
?>