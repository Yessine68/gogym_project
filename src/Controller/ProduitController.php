<?php

namespace App\Controller;

use App\Entity\Produit;
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
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
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
        ]);
    }
    #[Route('/front', name: 'app_produit_afficher_front', methods: ['GET'])]
    public function afficherfront(Request $request, ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        $products = $produitRepository->getProdsforPag();
        $adapter = new QueryAdapter($products);
        $pagination = new Pagerfanta($adapter);
        $pagination->setMaxPerPage(8);
        $pagination->setCurrentPage($request->query->get('page', 1));


        return $this->render('produit/showfront.html.twig', [
            'produits' => $pagination,
            'categories' => $categorieRepository->findAll(),
            
        ]);
        
    }
    #[Route('/afficher', name: 'app_produit_afficher', methods: ['GET'])]
    public function afficher(ProduitRepository $produitRepository,CategorieRepository $categorieRepository): Response
    {
        return $this->render('produit/afficher.html.twig', [
            'produits' => $produitRepository->findAll(),
            //'categories' => $categorieRepository->findAll(),
            
        ]);
        
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
    {
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
    #[Route('/item/{id}', name: 'app_produit_afficher_item', methods: ['GET'])]
    public function afficheritem(Produit $produit): Response
    {
        return $this->render('produit/showitem.html.twig', [
            'produit' => $produit,
            //'categories' => $categorieRepository->findAll(),
            
        ]);
        
    }
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    


    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_afficher', [], Response::HTTP_SEE_OTHER);
            
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_afficher', [], Response::HTTP_SEE_OTHER);
  
  
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
     {
 
         $em = $this->getDoctrine()->getManager();
         $produit = $em->getRepository(Produit::class)->find($id);
         $produit->setNomProd($req->get('nom_Prod'));
         $produit->setDescription($req->get('description'));
         $produit->setImage($req->get('image'));
         $produit->setPrix($req->get('prix'));
         $produit->setNbrProds($req->get('nbr_Prods'));
        
       
        
         $em->flush();
 
         $jsonContent = $Normalizer->normalize( $produit, 'json', ['groups' => 'produit']);
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



  
    







