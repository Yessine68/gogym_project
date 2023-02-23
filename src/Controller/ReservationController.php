<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ReservationRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Reservation;

use App\Form\ReservationType; 

use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{

    #[Route('/Reservation/Read/Front', name: 'FRead_Reservation')]
    public function fReadReservation(ReservationRepository $repo): Response
    {
        $Reservations = $repo->findAll();
        
        return $this->render('reservation/FReadReservation.html.twig', ["Reservations"=>$Reservations]);
    }

    #[Route('/Reservation/Read/Back', name: 'BRead_Reservation')]
    public function bReadReservation(ReservationRepository $repo): Response
    {
        $Reservations = $repo->findAll();
        
        return $this->render('reservation/BReadReservation.html.twig', ["Reservations"=>$Reservations]);
    }

    #[Route('/Reservation/Create', name: 'Create_Reservation')]
    public function CreateReservation(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$Reservation);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Reservation);
            $em->flush();
            return $this->redirectToRoute('BRead_Reservation');
        }

        return $this->renderForm('reservation/CreateReservation.html.twig',['form'=>$form]);
    
    }

    #[Route('/Reservation/Delete/{id}', name: 'Delete_Reservation')]
    public function DeleteReservation(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Reservation::class);
        $Reservation= $repo->find($id);
        $em->remove($Reservation);
        $em->flush();

        return $this->redirectToRoute('BRead_Reservation');
    }

    #[Route('/Reservation/Update/{id}', name: 'Update_Reservation')]
    public function UpdateReservation(ManagerRegistry $doctrine, $id, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Reservation = $doctrine->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class,$Reservation);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($Reservation);
            $em->flush();
            return $this->redirectToRoute('BRead_Reservation');
        }

        return $this->renderForm('reservation/UpdateReservation.html.twig',['form'=>$form]);

    }

}
?>