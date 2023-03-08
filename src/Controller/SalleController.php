<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Salle;
use App\Form\SalleType; 

use App\Repository\SalleRepository;

use Doctrine\Persistence\ManagerRegistry;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{

    #[Route('/Salle/Read_Back', name: 'Read_Back_Salle')]
    public function ReadBackSalle(SalleRepository $repo, Request $req, ManagerRegistry $doctrine): Response
    {
        $Salles = $repo->findAll();
        
        $query = $req->query->get('q');

        $Salles = $doctrine->getRepository(Salle::class)->findByNomOrVille($query);

        return $this->render('Salle/ReadSalleBack.html.twig', ["Salles"=>$Salles, "query"=>$query]);
    }

    #[Route('/Salle/Read_Front', name: 'Read_Front_Salle')]
    public function ReadFrontSalle(SalleRepository $repo, PaginatorInterface $paginator, Request $req, ManagerRegistry $doctrine): Response
    {
        $data = $repo->findAll();
        
        $SallesR = $repo->findAll();

        $query = $req->query->get('q');

        $SallesR = $doctrine->getRepository(Salle::class)->findByNomOrVille($query);

        $Salles = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );

        return $this->render('Salle/ReadSalleFront.html.twig', ["Salles"=>$Salles, "SallesR"=>$SallesR, "query"=>$query]);
    }

    #[Route('/Salle/Read_Front/{id}', name: 'Read_Front_One_Salle')]
    public function showSalle(Salle $salle): Response
    {
        return $this->render('salle/ReadOneSalleFront.html.twig', [
            'salle' => $salle,
        ]);
    }
    
    #[Route('/Salle/Create', name: 'Create_Salle')]
    public function CreateSalle(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Salle = new Salle();
        $form = $this->createForm(SalleType::class,$Salle);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $req->files->get('salle')['image_s'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $Salle->setImageS($filename);

            $em->persist($Salle);
            $em->flush();

            $this->addFlash('notice','Ajout avec success!');

            return $this->redirectToRoute('Read_Back_Salle');
        }

        return $this->renderForm('Salle/CreateSalle.html.twig',['form'=>$form]);
    
    }

    #[Route('/Salle/Delete/{id}', name: 'Delete_Salle')]
    public function DeleteSalle(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Salle::class);
        $Salle= $repo->find($id);
        $em->remove($Salle);
        $em->flush();

        $this->addFlash('notice','Suppression avec success!');

        return $this->redirectToRoute('Read_Back_Salle');
    }

    #[Route('/Salle/Update/{id}', name: 'Update_Salle')]
    public function UpdateSalle(ManagerRegistry $doctrine, $id, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Salle = $doctrine->getRepository(Salle::class)->find($id);
        $form = $this->createForm(SalleType::class,$Salle);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $req->files->get('salle')['image_s'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($uploads_directory, $filename);
            $Salle->setImageS($filename);
            
            $em->persist($Salle);
            $em->flush();
            
            $this->addFlash('notice','Modification avec success!');

            return $this->redirectToRoute('Read_Back_Salle');
        }

        return $this->renderForm('Salle/UpdateSalle.html.twig',['form'=>$form]);

    }

    #[Route('/Salle/Like/{id}', name: 'Like_Salle')]
    public function likeSalle(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $Salle = $doctrine->getRepository(Salle::class)->find($id);
        
        if($Salle->getLikeS()==0)
            $Salle->setLikeS($Salle->getLikeS()+1);
        else
            $Salle->setLikeS($Salle->getLikeS()-1);
        
        $em->persist($Salle);
        $em->flush();

        return $this->redirectToRoute('Read_Front_Salle');
    }

    #[Route('/Salle/generate-pdf', name: 'salle_generate_pdf')]
    public function generatePdfSalle(SalleRepository $repo, Pdf $pdf)
    {

        $Salles = $repo->findAll();

        $html = $this->renderView('salle/pdf.html.twig', [
            'Salles' => $Salles,
        ]);
        
        $pdfContent = $pdf->getOutputFromHtml($html);

        return new Response(
            $pdfContent,
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="salle.pdf"',
            )
        );
    }

    #[Route('/Salle/Find', name: 'Find_Salle')]
    public function FindSalle(Request $req, ManagerRegistry $doctrine): Response
    {
        $query = $req->query->get('q');

        $Salles = $doctrine->getRepository(Salle::class)->findByNomOrVille($query);

        return $this->render('Salle/search.html.twig', ["Salles"=>$Salles, "query"=>$query]);
    }

    #[Route('/Salle/FindFront', name: 'Find_Salle_Front')]
    public function FindSalleFront(ManagerRegistry $doctrine, SalleRepository $repo, Request $req, PaginatorInterface $paginator): Response
    {
        $data = $repo->findAll();

        $query = $req->query->get('q');

        $SallesR = $doctrine->getRepository(Salle::class)->findByNomOrVille($query);

        $Salles = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );

        return $this->render('Salle/searchFront.html.twig', ["Salles"=>$Salles, "SallesR"=>$SallesR, "query"=>$query]);
    }

    #[Route('/Salle/sortByAscPerimetre', name: 'sortByAscPerimetre_Salle')]
    public function sortByAscPerimetreSalle(SalleRepository $repo, Request $req): Response
    {
        $query = $req->query->get('q');

        $sortByAscPerimetreSalle = $repo->sortByAscPerimetre();

        return $this->render('Salle/ReadSalleBack.html.twig', [
            'Salles' => $sortByAscPerimetreSalle, "query"=>$query
        ]);
    }

    #[Route('/Salle/sortByDescPerimetre', name: 'sortByDescPerimetre_Salle')]
    public function sortByDescPerimetreSalle(SalleRepository $repo, Request $req): Response
    {
        $query = $req->query->get('q');

        $sortByDescPerimetreSalle = $repo->sortByDescPerimetre();

        return $this->render('Salle/ReadSalleBack.html.twig', [
            'Salles' => $sortByDescPerimetreSalle, "query"=>$query
        ]);
    }

}
