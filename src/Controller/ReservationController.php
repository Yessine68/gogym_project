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

    #[Route('/Reservation/Read', name: 'Read_Reservation')]
    public function ReadReservation(ReservationRepository $repo): Response
    {
        $Reservations = $repo->findAll();
        
        return $this->render('Reservation/ReadReservation.html.twig', ["Reservations"=>$Reservations]);
    }

    #[Route('/Reservation/Create', name: 'Create_Reservation')]
    public function CreateReservation(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class,$Reservation);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Reservation);
            $em->flush();
            return $this->redirectToRoute('Read_Reservation');
        }

        return $this->renderForm('Reservation/CreateReservation.html.twig',['form'=>$form]);
    
    }

    #[Route('/Reservation/Delete/{id_res}', name: 'Delete_Reservation')]
    public function DeleteReservation(ManagerRegistry $doctrine, $id_res): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Reservation::class);
        $Reservation= $repo->find($id_res);
        $em->remove($Reservation);
        $em->flush();

        return $this->redirectToRoute('Read_Reservation');
    }

    #[Route('/Reservation/Update/{id_res}', name: 'Update_Reservation')]
    public function UpdateReservation(ManagerRegistry $doctrine, $id_res, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Reservation = $doctrine->getRepository(Reservation::class)->find($id_res);
        $form = $this->createForm(ReservationType::class,$Reservation);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {
            $em->persist($Reservation);
            $em->flush();
            return $this->redirectToRoute('Read_Reservation');
        }

        return $this->renderForm('Reservation/CreateReservation.html.twig',['form'=>$form]);

    }

}
?>