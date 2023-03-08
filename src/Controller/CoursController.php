<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CoursRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Cours;

use App\Entity\Reservation;

use App\Form\CoursType; 
use App\Form\ReservationType; 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
<<<<<<< Updated upstream
=======
use DateTimeImmutable;
>>>>>>> Stashed changes

class CoursController extends AbstractController
{

    #[Route('/Cours/Read/Front', name: 'FRead_Cours')]
    public function FReadCours(CoursRepository $repo): Response
    {
        $Cours = $repo->findAll();
        
        return $this->render('Cours/FReadCours.html.twig', ["Cours"=>$Cours]);
    }

    #[Route('/Cours/Read/Back', name: 'BRead_Cours')]
    public function BReadCours(CoursRepository $repo,Request $request): Response
    {
        $Cours = $repo->findAll();
        
        $query = $request->query->get('q');
        $Cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->searchCours($query);
                                        
        
        return $this->render('Cours/BReadCours.html.twig', ["Cours"=>$Cours, "query"=>$query]);
    }
    
    #[Route('/Cours/Create', name: 'Create_Cours')]
    public function CreateCours(ManagerRegistry $doctrine, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = new Cours();
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $req->files->get('cours')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $Cours->setImage($filename);
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('BRead_Cours');
        }

        return $this->renderForm('Cours/CreateCours.html.twig',['form'=>$form]);
    
    }

    #[Route('/Cours/Delete/{id}', name: 'Delete_Cours')]
    public function DeleteCours(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Cours::class);
        $Cours= $repo->find($id);
        $em->remove($Cours);
        $em->flush();

        return $this->redirectToRoute('BRead_Cours');
    }

    #[Route('/Cours/Update/{id}', name: 'Update_Cours')]
    public function UpdateCours(ManagerRegistry $doctrine, $id, Request $req): Response 
    {
        $em = $doctrine->getManager();
        $Cours = $doctrine->getRepository(Cours::class)->find($id);
        $form = $this->createForm(CoursType::class,$Cours);
        $form->handleRequest($req); 

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $req->files->get('cours')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $Cours->setImage($filename);
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute('BRead_Cours');
        }

        return $this->renderForm('Cours/UpdateCours.html.twig',['form'=>$form]);

    }
    
    #[Route('/reserver/{id}', name: 'reserver_cours', methods: ['GET', 'POST'])]
    public function reserverCours(ManagerRegistry $doctrine, Request $req, $id): Response 
    {
        $date = new DateTimeImmutable();
        $em = $doctrine->getManager();
        $Reservation = new Reservation();
        $repo= $doctrine->getRepository(Cours::class);
        $Cours= $repo->find($id);
        $Reservation->setType('cours');
        $Reservation->setDate($date);
            $Reservation->setCours($Cours);
            $em->persist($Reservation);
            $em->flush();
            
            return $this->redirectToRoute('FRead_Cours');

        return $this->renderForm('Cours/FReadCours.html.twig',[
            'form'=>$form,
            'Cours' => $Cours
        ]);
    
    }
    
    /**
     * @Route("/search", name="cours_search")
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q');
        $Cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->searchCours($query);

        return $this->render('cours/search.html.twig', [
            'Cours' => $Cours,
            'query' => $query,
        ]);
    }

//* Partie Mobile

    #[Route("/Allcours", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCours(CoursRepository $repo, SerializerInterface $serializer)
    {
        $Cours = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
         //$studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "cours"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
         //$json = json_encode($studentsNormalises);

        $json = $serializer->serialize($Cours, 'json', ['groups' => "cours"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

//* Partie Mobile

    #[Route("/Allcours", name: "list")]
    //* Dans cette fonction, nous utilisons les services NormlizeInterface et StudentRepository, 
    //* avec la méthode d'injection de dépendances.
    public function getCours(CoursRepository $repo, SerializerInterface $serializer)
    {
        $Cours = $repo->findAll();
        //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
        //* students en  tableau associatif simple.
         //$studentsNormalises = $normalizer->normalize($students, 'json', ['groups' => "cours"]);

        // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
         //$json = json_encode($studentsNormalises);

        $json = $serializer->serialize($Cours, 'json', ['groups' => "cours"]);

        //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
        return new Response($json);
    }

}
?>