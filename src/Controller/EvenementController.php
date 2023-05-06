<?php

namespace App\Controller;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Repository\CategorieEvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Evenement;
use App\Entity\CategorieEvenement;
use App\Entity\Participate;
 use App\Form\EvenementType;
use App\Services\QrcodeService;
use Symfony\Component\HttpFoundation\Request;
 
use Symfony\Component\Serializer\SerializerInterface;
 
 
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/Allevent', name: 'Allevent')]
    public function Allevent(EvenementRepository $evenementRepository , SerializerInterface $serializer)
    {
        $event = $evenementRepository->findAll();
        $json = $serializer->serialize($event, 'json', ['groups' => "event"]);
        return new Response($json);
    }

    // #[Route("/DemandeUserCarteId/{id}", name: "DemandeUserCarteId")]
    // public function DemandeUserCarteId($id, NormalizerInterface $normalizer,CarteBancaireRepository $CarteBancaireRepository)
    // {
    //     $CarteBancaire = $CarteBancaireRepository->find($id);
    //     $CarteBancaireNormalises = $normalizer->normalize($CarteBancaire, 'json', ['groups' => "CarteBancaire"]);
    //     return new Response(json_encode($CarteBancaireNormalises));
    // }


    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {    $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            'notifications' => $notifications,
            
        ]);
        return $this->render('evenement/afficher.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            'notifications' => $notifications,
            
        ]);
        
    }
    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $evenement = new Evenement();
        $form = $this->createForm( EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newImage = $form->get('imageE')->getData();

            if ($newImage) {
               
                // Génération d'un nom de fichier unique
                $filename = uniqid().'.'.$newImage->guessExtension();
            
                // Déplacement du fichier dans le dossier des images
                try {
                    $newImage->move(
                        $this->getParameter('images_directoryE'),
                        $filename
                    );
                } catch (FileException) {
                    // Gestion de l'erreur
                }
            
                // Stockage du nom de fichier dans l'entité Événement
                $evenement->setImage($filename);
                $evenement->setEtat("incomplet");
            }
            
            // Sauvegarde de l'entité Événement dans la base de données
            $evenementRepository->save($evenement, true);
            $this->addFlash('success' , 'success');

            return $this->redirectToRoute('app_evenement_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/afficher', name: 'app_evenement_afficher', methods: ['GET'])]
    public function afficher(EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
     
        return $this->render('evenement/afficher.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'notifications' => $notifications,
            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
    #[Route('/afficherc', name: 'app_evenement_afficherc', methods: ['GET'])]
    public function affichercalendar(EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $lista = $this->getDoctrine()->getRepository(Evenement::class)->findAll();

        $resa = [] ;

        foreach ($lista as $xa)
        {
            $resa[] = [
                //'id'=> $x->getId(),
                'title'=>$xa->getNomE(),
                //'Client'=>$x->getClient()->getName() ,
                'start'=>$xa->getDateE(),
                'end'=>$xa->getDateE(),
            ] ;

        }

        $dataa = json_encode($resa);
        return $this->render('dashboard/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'dataa'=>$dataa ,
            'notifications' => $notifications,

            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
    #[Route('/front', name: 'app_evenement_afficher_front', methods: ['GET'])]
    public function afficherfront(Request $request, EvenementRepository $evenementRepository,CategorieEvenementRepository $categorieEvenementRepository): Response
    {
        return $this->render('evenement/showfront.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            
        ]);
        
    }
  
    #[Route('/blog/{id}', name: 'app_evenement_afficher_blog', methods: ['GET'])]
    public function afficherblog(Evenement $evenement,Request $req, $id,EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/showblog.html.twig', [
            'event' => $evenementRepository->find($id),
            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
        ]);
        
    }
    



    

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {      $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newImage = $form->get('imageE')->getData();
                if ($newImage) {
                    // Génération d'un nom de fichier unique
                    $filename = uniqid().'.'.$newImage->guessExtension();
                
                    // Déplacement du fichier dans le dossier des images
                    try {
                        $newImage->move(
                            $this->getParameter('images_directoryE'),
                            $filename
                        );
                    } catch (FileException $e) {
                        // Gestion de l'erreur
                    }
                
                    // Stockage du nom de fichier dans l'entité Événement
                    $evenement->setImage($filename);
                }
                
                // Sauvegarde de l'entité Événement dans la base de données
                $evenementRepository->save($evenement, true);

            return $this->redirectToRoute('app_evenement_afficher', [
                'notifications' => $notifications,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'notifications' => $notifications,
        ]);
    }


    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement, true);
        }

        return $this->redirectToRoute('app_evenement_afficher', [], Response::HTTP_SEE_OTHER);
    }
    
 
    public function email($nameUser,$nameEvent,$email, \Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message('confirmation de reservation pour evenement '))
            ->setFrom('ibrahim.souissi@esprit.tn')
            ->setTo('ibrahim.souissi@esprit.tn');
        $img = $message->embed(\Swift_Image::fromPath('QRcode/client'.$nameUser.'nomEvent'.$nameEvent.'.png')); // your qr code
        $img8 = $message->embed(\Swift_Image::fromPath('email/image-8.jpeg')); // logo 

        $message
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $nameUser,
                        'img'  =>$img,
                        'nomEvent'=> $nameEvent,
                        'img8'=>$img8,
                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);

    }
    #[Route('/ParticiperEvent/{id}', name: 'ParticiperEvent')]

    public function ParticiperEvent(QrcodeService $qrcodeService, EvenementRepository $repository,Request $req, $id,\Swift_Mailer $mailer, Security $security) {
//id_part
//id_event
//verif_code
        // get id user from session 
        $user = $security->getUser();
        $iduser= $user->getId();
        $em= $this->getDoctrine()->getManager();

        // $user = $em->getRepository(User::class)->find($iduser);

        $email=  $user->getEmail();// change it to email from session  get email 
        $name= $user->getNom(); ; // ->getnom
        $Event = $em->getRepository(Evenement::class)->find($id);
        $eventId=$Event->getId();
        $nameEvent=$Event->getNomE();
        $CategoriesEvent = $this->getDoctrine()->getManager()->getRepository(CategorieEvenement::class)->findAll();

        //TEST PARTICIPATION:
      //  $currentEvt =$em->getRepository(Participation::class)->findBy(["idEvent"=>$id]);
        $userr =$em->getRepository(Participate::class)->findUserinEvent($iduser,$eventId);

        if ($Event->getNbrParticipants()==1){
            $Event->setEtat("Complet");
        }


        if (  !$userr && $Event->getNbrParticipants()!=0)
        {
            $Participation= new Participate();
            $em= $this->getDoctrine()->getManager();
            $Event = $em->getRepository(Evenement::class)->find($id);
            $nbr=$Event->getNbrParticipants();
            $Event->setNbrParticipants($nbr-1);
            $Participation->setIdUser($user);
            $Participation->setIdEvent($Event);
            $random = random_int(1000, 9000);
            $Participation->setVerificationCode($random);
            $qrCode = $qrcodeService->qrcode($name,$nameEvent,$random);
            $this->email($name,$nameEvent,$email,$mailer);
            $em->persist($Participation);
            $em->flush();
            $reject="true" ;


            return $this->render('evenement/showblog.html.twig', [
                'event' => $repository->find($id),
                $reject="true" ,
                //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
            ]);
        }

        $this->addFlash('error' , 'Vous avez deja participe');
        $reject="true" ;

        return $this->render('evenement/showblog.html.twig', [
            'event' => $repository->find($id),
            $reject="true" ,
            //'CategorieEvenements' => $categorieEvenementRepository->findAll(),
        ]);

    }

}
