<?php
namespace App\Controller\Mobile;

use App\Entity\Salle;
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
 * @Route("/mobile/salle")
 */
class SalleMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(SalleRepository $salleRepository): Response
    {
        $salles = $salleRepository->findAll();

        if ($salles) {
            return new JsonResponse($salles, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $salle = new Salle();

        return $this->manage($salle, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, SalleRepository $salleRepository): Response
    {
        $salle = $salleRepository->find((int)$request->get("id"));

        if (!$salle) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($salle, $request, true);
    }

    public function manage($salle, $request, $isEdit): JsonResponse
    {   
        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('uploads_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }
        
        $salle->constructor(
            $request->get("nom"),
            (int)$request->get("tel"),
            $request->get("email"),
            $request->get("adresse"),
            $request->get("ville"),
            (int)$request->get("perimetre"),
            $imageFileName,
            (int)$request->get("longitude_s"),
            (int)$request->get("latitude_s"),
            (int)$request->get("likes")
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($salle);
        $entityManager->flush();

        return new JsonResponse($salle, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, SalleRepository $salleRepository): JsonResponse
    {
        $salle = $salleRepository->find((int)$request->get("id"));

        if (!$salle) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($salle);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, SalleRepository $salleRepository): Response
    {
        $salles = $salleRepository->findAll();

        foreach ($salles as $salle) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('uploads_directory') . "/" . $request->get("image")
        );
    }
    
}
