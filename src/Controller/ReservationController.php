<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ReservationRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Reservation;
use App\Entity\Cours;

use App\Form\ReservationType;
use App\Form\FilterType;

use Symfony\Component\HttpFoundation\Request;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Validator\Constraints\DateTime;

class ReservationController extends AbstractController
{
    #[Route('/Reservation/Read/Front', name: 'FRead_Reservation')]
    public function fReadReservation(ReservationRepository $repo): Response
    {
        $Reservations = $repo->findAll();

        return $this->render('reservation/FReadReservation.html.twig', [
            'Reservations' => $Reservations,
        ]);
    }
    
    #[Route('/sortByAscDate', name: 'sort_by_asc_date')]
    public function sortAscDate(ReservationRepository $repository, Request $request)
    {
        $Reservations = $repository->findAll();
        $Reservations = $repository->sortByAscDate();       
        
        $data = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBy([], ['id' => 'desc']);

        $pieChart = new PieChart();

        $reservationsData = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();
        $coursData = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        // dd($coursData);

        $charts = [['Reservations', 'Nombre par Cours']];
        // dd($charts);
        foreach ($coursData as $c) {
            $coursN = 0;
            foreach ($reservationsData as $r) {
                if ($c == $r->getCours()) {
                    $coursN++;
                }
            }

            array_push($charts, [$c->getNom(), $coursN]);
        }

        // dd($charts);

        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Taux de réservations par Cours');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);

        $rsrvs = [];

        foreach ($Reservations as $reservation) {
            $rsrvs[] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'type' => $reservation->getType(),
                'cours' => $reservation->getCours()->getNom(),
                'start' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'end' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'title' => $reservation->getCours()->getNom(),
                'description' => $reservation->getType(),
                'backgroundColor' => $reservation->getType(),
                'borderColor' => $reservation->getType(),
                'textColor' => $reservation->getType(),
                'allDay' => $reservation->getType(),
            ];
        }

        $data = json_encode($rsrvs);

        // dd($data);
        
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $reservations = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData()['date'];

            $reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findBy(['date' => $date]);
        } else {
            $reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findAll();
        }

       
    
        return $this->render("reservation/BReadReservation.html.twig",[
            'Reservations' => $Reservations,
            'piechart' => $pieChart,
            'data' => $data,
            'form' => $form->createView(),
            'reservations' => $reservations,
        ]);
    }
    
    #[Route('/sortByDescDate', name: 'sort_by_desc_date')]
    public function sortDescDate(ReservationRepository $repository, Request $request)
    {
        $Reservations = $repository->findAll();
        $Reservations = $repository->sortByDescDate();    
        
        $data = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBy([], ['id' => 'desc']);

        $pieChart = new PieChart();

        $reservationsData = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();
        $coursData = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        // dd($coursData);

        $charts = [['Reservations', 'Nombre par Cours']];
        // dd($charts);
        foreach ($coursData as $c) {
            $coursN = 0;
            foreach ($reservationsData as $r) {
                if ($c == $r->getCours()) {
                    $coursN++;
                }
            }

            array_push($charts, [$c->getNom(), $coursN]);
        }

        // dd($charts);

        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Taux de réservations par Cours');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);

        $rsrvs = [];

        foreach ($Reservations as $reservation) {
            $rsrvs[] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'type' => $reservation->getType(),
                'cours' => $reservation->getCours()->getNom(),
                'start' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'end' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'title' => $reservation->getCours()->getNom(),
                'description' => $reservation->getType(),
                'backgroundColor' => $reservation->getType(),
                'borderColor' => $reservation->getType(),
                'textColor' => $reservation->getType(),
                'allDay' => $reservation->getType(),
            ];
        }

        $data = json_encode($rsrvs);

        // dd($data);
        
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $reservations = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData()['date'];

            $reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findBy(['date' => $date]);
        } else {
            $reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findAll();
        }

       
    
        return $this->render("reservation/BReadReservation.html.twig",[
            'Reservations' => $Reservations,
            'piechart' => $pieChart,
            'data' => $data,
            'form' => $form->createView(),
            'reservations' => $reservations,
        ]);
    }

    #[Route('/Reservation/Read/Back', name: 'BRead_Reservation')]
    public function bReadReservation(ReservationRepository $repo, Request $request): Response
    {
        $Reservations = $repo->findAll();

        $data = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findBy([], ['id' => 'desc']);

        $pieChart = new PieChart();

        $reservationsData = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();
        $coursData = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        // dd($coursData);

        $charts = [['Reservations', 'Nombre par Cours']];
        // dd($charts);
        foreach ($coursData as $c) {
            $coursN = 0;
            foreach ($reservationsData as $r) {
                if ($c == $r->getCours()) {
                    $coursN++;
                }
            }

            array_push($charts, [$c->getNom(), $coursN]);
        }

        // dd($charts);

        $pieChart->getData()->setArrayToDataTable($charts);

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Taux de réservations par Cours');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setColor('#07600');
        $pieChart
            ->getOptions()
            ->getTitleTextStyle()
            ->setFontSize(25);

        $rsrvs = [];

        foreach ($Reservations as $reservation) {
            $rsrvs[] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'type' => $reservation->getType(),
                'cours' => $reservation->getCours()->getNom(),
                'start' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'end' => $reservation->getDate()->format('Y-m-d H:i:s'),
                'title' => $reservation->getCours()->getNom(),
                'description' => $reservation->getType(),
                'backgroundColor' => $reservation->getType(),
                'borderColor' => $reservation->getType(),
                'textColor' => $reservation->getType(),
                'allDay' => $reservation->getType(),
            ];
        }

        $data = json_encode($rsrvs);

        // dd($data);
        
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        $Reservations = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->getData()['date'];

            $Reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findBy(['date' => $date]);
        } else {
            $Reservations = $this->getDoctrine()
                ->getRepository(Reservation::class)
                ->findAll();
        }

        return $this->render('reservation/BReadReservation.html.twig', [
            'Reservations' => $Reservations,
            'piechart' => $pieChart,
            'data' => $data,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Reservation/Create', name: 'Create_Reservation')]
    public function CreateReservation(
        ManagerRegistry $doctrine,
        Request $req,
    ): Response {
        $em = $doctrine->getManager();
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $Reservation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Reservation);
            $em->flush();

            return $this->redirectToRoute('BRead_Reservation');
        }

        return $this->renderForm('reservation/CreateReservation.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/Reservation/Delete/{id}', name: 'Delete_Reservation')]
    public function DeleteReservation(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository(Reservation::class);
        $Reservation = $repo->find($id);
        $em->remove($Reservation);
        $em->flush();

        return $this->redirectToRoute('BRead_Reservation');
    }

    #[Route('/Reservation/Update/{id}', name: 'Update_Reservation')]
    public function UpdateReservation(
        ManagerRegistry $doctrine,
        $id,
        Request $req
    ): Response {
        $em = $doctrine->getManager();
        $Reservation = $doctrine->getRepository(Reservation::class)->find($id);
        $form = $this->createForm(ReservationType::class, $Reservation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Reservation);
            $em->flush();
            return $this->redirectToRoute('BRead_Reservation');
        }

        return $this->renderForm('reservation/UpdateReservation.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="api_reservation_edit", methods={"PUT"})
     */
    public function MajReservation(Reservation $reservation, Request $request)
    {
        // On récupère les données
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->type) &&
            !empty($donnees->type) &&
            isset($donnees->date) &&
            !empty($donnees->date) &&
            isset($donnees->cours) &&
            !empty($donnees->cours) &&
            isset($donnees->title) &&
            !empty($donnees->title) &&
            isset($donnees->start) &&
            !empty($donnees->start) &&
            isset($donnees->description) &&
            !empty($donnees->description) &&
            isset($donnees->backgroundColor) &&
            !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) &&
            !empty($donnees->borderColor) &&
            isset($donnees->textColor) &&
            !empty($donnees->textColor)
        ) {
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if (!$reservation) {
                // On instancie un rendez-vous
                $reservation = new Reservation();

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $reservation->setType($donnees->type);
            $reservation->setDate($donnees->date);
            $reservation->setCours($donnees->cours);
            $reservation->setTitle($donnees->title);
            $reservation->setDescription($donnees->description);
            $reservation->setStart($donnees->start);
            $reservation->setEnd($donnees->start);
            $reservation->setAllDay($donnees->allDay);
            $reservation->setBackgroundColor($donnees->backgroundColor);
            $reservation->setBorderColor($donnees->borderColor);
            $reservation->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        } else {
            // Les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
?>