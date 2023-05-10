<?php
namespace App\Controller\Mobile;

use App\Entity\Participate;
use App\Repository\ParticipateRepository;
use App\Repository\UserRepository;
use App\Repository\EvenementRepository;
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
 * @Route("/mobile/participate")
 */
class ParticipateMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ParticipateRepository $participateRepository): Response
    {
        $participates = $participateRepository->findAll();

        if ($participates) {
            return new JsonResponse($participates, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UserRepository $userRepository, EvenementRepository $evenementRepository): JsonResponse
    {
        $participate = new Participate();

        return $this->manage($participate, $userRepository,  $evenementRepository,  $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ParticipateRepository $participateRepository, UserRepository $userRepository, EvenementRepository $evenementRepository): Response
    {
        $participate = $participateRepository->find((int)$request->get("id"));

        if (!$participate) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($participate, $userRepository, $evenementRepository, $request, true);
    }

    public function manage($participate, $userRepository, $evenementRepository, $request, $isEdit): JsonResponse
    {   
        $user = $userRepository->find((int)$request->get("user"));
        if (!$user) {
            return new JsonResponse("user with id " . (int)$request->get("user") . " does not exist", 203);
        }
        
        $evenement = $evenementRepository->find((int)$request->get("evenement"));
        if (!$evenement) {
            return new JsonResponse("evenement with id " . (int)$request->get("evenement") . " does not exist", 203);
        }
        
        
        $participate->constructor(
            $user,
            $evenement
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($participate);
        $entityManager->flush();

        return new JsonResponse($participate, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ParticipateRepository $participateRepository): JsonResponse
    {
        $participate = $participateRepository->find((int)$request->get("id"));

        if (!$participate) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($participate);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, ParticipateRepository $participateRepository): Response
    {
        $participates = $participateRepository->findAll();

        foreach ($participates as $participate) {
            $entityManager->remove($participate);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
