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

use Symfony\Component\HttpFoundation\Request;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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

    #[Route('/Reservation/Read/Back', name: 'BRead_Reservation')]
    public function bReadReservation(ReservationRepository $repo): Response
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

        return $this->render('reservation/BReadReservation.html.twig', [
            'Reservations' => $Reservations,
            'piechart' => $pieChart,
        ]);
    }

    #[Route('/Reservation/Create', name: 'Create_Reservation')]
    public function CreateReservation(
        ManagerRegistry $doctrine,
        Request $req,
        MailerInterface $mailer
    ): Response {
        $em = $doctrine->getManager();
        $Reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $Reservation);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Reservation);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from('fourat.abdellatif@esprit.tn')
                ->to('fourat.abdellatifo99@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->htmlTemplate('email/welcome.html.twig')
                ->context([
                    'Reservation' => $Reservation,
                ]);

            $mailer->send($email);
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
}
?>
