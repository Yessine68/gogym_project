<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\PieChart\PieSlice;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Notification;
use App\Repository\NotificationRepository;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            
        ]);
        return $this->render('produit/afficher.html.twig', [
            'produits' => $produitRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
            
        ]);
        
    }
   
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, Security $security): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        if ($security->isGranted('ROLE_ADMIN')) {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // Génération d'un nom de fichier unique
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacement du fichier dans le dossier des images
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gestion de l'erreur
                }

                // Stockage du nom de fichier dans l'entité Produit
                $produit->setImage($newFilename);
            }

            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_afficher', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'notifications' => $notifications,
        ]);}
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('afterlogin');}
            return $this->redirectToRoute('app_login');
    }
    #[Route('/front', name: 'app_produit_afficher_front', methods: ['GET'])]
    public function afficherfront(Request $request, ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        $products = $produitRepository->getProdsforPag();
        $adapter = new QueryAdapter($products);
        $pagination = new Pagerfanta($adapter);
        $pagination->setMaxPerPage(8);
        $pagination->setCurrentPage($request->query->get('page', 1));
        $meilleurproduits = $produitRepository->getProdsbyRating();

        return $this->render('produit/showfront.html.twig', [
            'produits' => $pagination,
            'categories' => $categorieRepository->findAll(),
            'meilleurprods' => $meilleurproduits,
            
        ]);
        
    }
    #[Route('/afficher', name: 'app_produit_afficher', methods: ['GET'])]
    public function afficher(ProduitRepository $produitRepository,CategorieRepository $categorieRepository, Security $security): Response
    {    $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        if ($security->isGranted('ROLE_ADMIN')) {
        return $this->render('produit/afficher.html.twig', [
            'produits' => $produitRepository->findAll(),
            'notifications' => $notifications,
            //'categories' => $categorieRepository->findAll(),        
        ]);}
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('afterlogin');}

            return $this->redirectToRoute('app_login');
    }
    
    #[Route('/search', name: 'app_produit_rechercher')]
     public function search(Request $request,CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
     {
         $produit = new Produit();
 
         $produits = [];
 
             $nom = $request->query->get('prod');
            
             $produits = $produitRepository->findProd($nom);

        return $this->render('produit/showfrontajax.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),

        ]);
    }
    #[Route('/stats', name: 'app_produit_stats')]
    public function stats(Request $request,CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
         $produit = new Produit();
 
         $produits = [];
 
            
             $produits = $produitRepository->getStat();
             $prods = array (array("Categories","Nombre de Produits"));
            $i = 1;
            foreach ($produits as $prod){
                $prods[$i] = array($prod["nom"],$prod["nbre"]);
                $i= $i + 1;
            }


             $bar = new BarChart();
            $bar->getData()->setArrayToDataTable(
                $prods
            );
            $bar->getOptions()->setTitle('Statistique de nombre de produits par categorie');
            $bar->getOptions()->getHAxis()->setTitle('Statistique de nombre de produits par categorie');
            $bar->getOptions()->getHAxis()->setMinValue(0);
            $bar->getOptions()->setWidth(900);
            $bar->getOptions()->setHeight(500);

             
        return $this->render('produit/afficherstats.html.twig', [
            'piechart' => $bar,
            'notifications' => $notifications,

        ]);
    }
    #[Route('/tricat/{id_cat}', name: 'app_produit_tri_cat')]
     public function tricat($id_cat,Request $request,CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
     {
         $produit = new Produit();
 
         $produits = [];
 
            
        $produits = $produitRepository->triProdByCat($id_cat);
        return $this->render('produit/showfront2.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),

        ]);
    }
    #[Route('/triprix', name: 'app_produit_tri_prix')]
     public function triprix(Request $request,CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
     {
         $produit = new Produit();
 
         $produits = [];
         $val = $request->query->get('val');
            
        $produits = $produitRepository->triProdByPrice($val);
        return $this->render('produit/showfrontajax.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),
        ]);
    }
    #[Route('/addrate', name: 'app_produit_add_rate')]
     public function addrate(Request $request,CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
     {
         $produit = new Produit();
 
         $produits = [];
         //$val = $request->query->get('val');
         $id_user = $request->query->get('id_user'); 
         $id_prod = $request->query->get('id_prod');
         $rate = $request->query->get('rate');

        $produits = $produitRepository->addrateprod($id_user,$id_prod,$rate);
        return $this->render('produit/showfront2.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),
        ]);
    }
    #[Route('/item/{id}', name: 'app_produit_afficher_item', methods: ['GET'])]
    public function afficheritem($id,Produit $produit,ProduitRepository $produitRepository, UserRepository $usersRepository  , Security $security): Response
    {
        // $user = $usersRepository->findOneByResetToken($token);
        $user_signed_in = $security->isGranted('IS_AUTHENTICATED_FULLY');
        $user = $security->getUser();
        $rate =  $user->getUsername(); // 0 si pas de rate - de 1 a 5 si c deja 
        $user_id = $user->getId(); //0 si non connecte - id si connecte
        $rates = $produitRepository->checkRateProd($user_id,$id); // check rate 
        
        if (!empty($rates)){
            $rate =  $rates[0]["rate"];
        }

        return $this->render('produit/showitem.html.twig', [
            'produit' => $produit,
            'user_signed_in' => $user_signed_in,
            'user_id' => $user_id,
            'rate' => $rate,
        ]);
        
    }
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'notifications' => $notifications,
        ]);
    }
    


    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_afficher', [], Response::HTTP_SEE_OTHER);
            
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_produit_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        //if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        //}

        return $this->redirectToRoute('app_produit_afficher', [
            'notifications' => $notifications,
        ], Response::HTTP_SEE_OTHER);
  
  
  }

  #[Route('/Allproduit/CarteJson', name: 'AllDemandeUserCarteJson')]
     public function AllDemandeUserCarteJson(ProduitRepository $produitRepository , SerializerInterface $serializer)
     {
        $produit = $produitRepository->findAll();
         $json = $serializer->serialize($produit, 'json', ['groups' => "produit"]);
         return new Response($json);
     }
 
     #[Route("/produitCarteId/{id}", name: "DemandeUserCarteId")]
     public function DemandeUserCarteId($id, NormalizerInterface $normalizer,ProduitRepository $produitRepository)
     {
        $produit = $CarteBancaireRepository->find($id);
         $ProduiteNormalises = $normalizer->normalize($produit, 'json', ['groups' => "produit"]);
         return new Response(json_encode($ProduiteNormalises));
     }

     #[Route("/updateproduit/{id}", name: "updateproduitJSON")]
     public function updateTypeCarnetsJSON(Request $req, $id, NormalizerInterface $Normalizer)
     {  $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
 
         $em = $this->getDoctrine()->getManager();
         $produit = $em->getRepository(Produit::class)->find($id);
         $produit->setNomProd($req->get('nom_Prod'));
         $produit->setDescription($req->get('description'));
         $produit->setImage($req->get('image'));
         $produit->setPrix($req->get('prix'));
         $produit->setNbrProds($req->get('nbr_Prods'));
        
       
        
         $em->flush();
 
         $jsonContent = $Normalizer->normalize( $produit, 'json', [
            'groups' => 'produit',
            'notifications' => $notifications,
        ]);
         return new Response("Type Carnet updated successfully " . json_encode($jsonContent));
     }
 
 
 
     #[Route("/addTypeCarnets/JSON/new", name: "addTypeCarnets")]
     public function addTypeCarnets(Request $req,   NormalizerInterface $Normalizer)
     {
 
         $em = $this->getDoctrine()->getManager();
         $produit = new Produit();
         
         $produit->setNomProd($req->get('nom'));
         $produit->setDescription($req->get('description'));
         $produit->setImage($req->get('image'));
         $produit->setPrix($req->get('prix'));
         $produit->setNbrProds($req->get('Quantite'));
         
         $em->persist($produit);
         $em->flush();
         $jsonContent = $Normalizer->normalize( $produit, 'json', ['groups' => 'produit']);
         return new Response(json_encode($jsonContent));
         
     }
 
     #[Route("/deleteTypesCarnetJSON/{id}", name: "deleteTypesCarnetJSON")]
     public function deleteTypesCarnetJSON(Request $req, $id, NormalizerInterface $Normalizer)
     {
 
         $em = $this->getDoctrine()->getManager();
         $produit = $em->getRepository(Produit::class)->find($id);
         $em->remove( $produit);
         $em->flush();
         $jsonContent = $Normalizer->normalize( $produit, 'json', ['groups' => 'produit']);
         return new Response("Carnet deleted successfully " . json_encode($jsonContent));
     }

     






}



  
    







