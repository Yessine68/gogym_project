<?php
namespace App\Controller\Mobile;

use App\Entity\CategorieEvenement;
use App\Repository\CategorieEvenementRepository;
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
 * @Route("/mobile/categorieEvenement")
 */
class CategorieEvenementMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $categorieEvenements = $categorieEvenementRepository->findAll();

        if ($categorieEvenements) {
            return new JsonResponse($categorieEvenements, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $categorieEvenement = new CategorieEvenement();

        return $this->manage($categorieEvenement, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $categorieEvenement = $categorieEvenementRepository->find((int)$request->get("id"));

        if (!$categorieEvenement) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($categorieEvenement, $request, true);
    }

    public function manage($categorieEvenement, $request, $isEdit): JsonResponse
    {   
        
        $categorieEvenement->constructor(
            $request->get("nom")
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categorieEvenement);
        $entityManager->flush();

        return new JsonResponse($categorieEvenement, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CategorieEvenementRepository $categorieEvenementRepository): JsonResponse
    {
        $categorieEvenement = $categorieEvenementRepository->find((int)$request->get("id"));

        if (!$categorieEvenement) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($categorieEvenement);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        $categorieEvenements = $categorieEvenementRepository->findAll();

        foreach ($categorieEvenements as $categorieEvenement) {
            $entityManager->remove($categorieEvenement);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
