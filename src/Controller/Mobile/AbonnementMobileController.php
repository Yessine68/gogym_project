<?php

namespace App\Controller\Mobile;

use App\Entity\Abonnement;
use App\Entity\Salle;
use App\Repository\AbonnementRepository;
use App\Repository\AbonnementsalleRepository;
use App\Repository\SalleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/abonnement")
 */
class AbonnementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository, AbonnementsalleRepository $abonnementSalleRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        $abonnementsArray = [];
        $index = 0;
        foreach ($abonnements as $abonnement) {
            $abonnementsArray[$index] = $abonnement->jsonSerialize();

            $abonnementSalles = $abonnementSalleRepository->findAll();

            $emptySalleArray = [];
            $emptySalleArray["id"] = 0;
            $emptySalleArray["name"] = null;

            if ($abonnements != []) {
                if ($abonnementSalles) {
                    if ($abonnementSalles[0]) {
                        $abonnementsArray[$index]["salle"] = $abonnementSalles[0];
                    } else {
                        $abonnementsArray[$index]["salle"] = $emptySalleArray;
                    }
                } else {
                    $abonnementsArray[$index]["salle"] = $emptySalleArray;
                }
            } else {
                $abonnementsArray[$index]["salle"] = $emptySalleArray;
            }

            $index++;
        }

        if ($abonnementsArray) {
            return new JsonResponse($abonnementsArray, 200);
        } else {
            return new JsonResponse([], 204);
        }

    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, SalleRepository $salleRepository): JsonResponse
    {
        $abonnement = new Abonnement();

        return $this->manage($abonnement, $salleRepository, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, AbonnementRepository $abonnementRepository, SalleRepository $salleRepository): Response
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($abonnement, $salleRepository, $request, true);
    }

    public function manage($abonnement, $salleRepository, $request, $isEdit): JsonResponse
    {


        $abonnement->constructor(
            $request->get("nom"),
            $request->get("type"),
            $request->get("description"),
            (int)$request->get("prix"),
            DateTime::createFromFormat("d-m-Y", $request->get("debut")),
            DateTime::createFromFormat("d-m-Y", $request->get("fin"))
        );


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnement);
        $entityManager->flush();

        return new JsonResponse($abonnement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository): JsonResponse
    {
        $abonnement = $abonnementRepository->find((int)$request->get("id"));

        if (!$abonnement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($abonnement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository): Response
    {
        $abonnements = $abonnementRepository->findAll();

        foreach ($abonnements as $abonnement) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

}

?>