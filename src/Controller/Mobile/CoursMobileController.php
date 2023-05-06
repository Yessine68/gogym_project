<?php
namespace App\Controller\Mobile;

use App\Entity\Cours;
use App\Repository\CoursRepository;
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
 * @Route("/mobile/cours")
 */
class CoursMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(CoursRepository $coursRepository): Response
    {
        $courss = $coursRepository->findAll();

        if ($courss) {
            return new JsonResponse($courss, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $cours = new Cours();

        return $this->manage($cours, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->find((int)$request->get("id"));

        if (!$cours) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($cours, $request, true);
    }

    public function manage($cours, $request, $isEdit): JsonResponse
    {   
        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('images_directory'), $imageFileName);
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
        
        $cours->constructor(
            $request->get("nom"),
            (int)$request->get("duree"),
            $request->get("intensite"),
            $request->get("bienfaits"),
            $imageFileName
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cours);
        $entityManager->flush();

        return new JsonResponse($cours, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, CoursRepository $coursRepository): JsonResponse
    {
        $cours = $coursRepository->find((int)$request->get("id"));

        if (!$cours) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($cours);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, CoursRepository $coursRepository): Response
    {
        $courss = $coursRepository->findAll();

        foreach ($courss as $cours) {
            $entityManager->remove($cours);
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
            $this->getParameter('images_directory') . "/" . $request->get("image")
        );
    }
    
}
