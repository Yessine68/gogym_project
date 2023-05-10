<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\CommandeRepository;
use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\name;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;


class LigneCommandeController extends AbstractController
{


    /**
     * @Route("/lignecommande", name="lignecommande")
     */
    public function show (LigneCommandeRepository $ligneCommandeRepository): Response
    {
        return $this->render('ligne_commande/back_lignecommande.html.twig', [
            'lignecommandes' => $ligneCommandeRepository->findAll(),
        ]);
    }


    /**
     * @Route("/panier1", name="panier1")
     * @param SessionInterface $session
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {

        $panier = $session->get('panier',[]);
        $total= 0;

        foreach($panier as $item){
            $totalitem = $item ['produit'] -> getPrixProduit() * $item['quantite_produit'];
            $total += $totalitem;
        }

        return $this-> render('ligne_commande/index.html.twig' , [
            'items' => $panier,
            'total'=> $total
        ]);

    }


    /**
     * @Route("/checkout", name="checkout")
     * @param SessionInterface $session
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function checkout(Request $request,SessionInterface $session, ProduitRepository $produitRepository): Response
    {



        $panier = $session->get('panier',[]);
        $total= 0;
        foreach($panier as $item){
            $totalitem = $item ['produit'] -> getPrixProduit() * $item['quantite_produit'];
            $total += $totalitem;
        }


        if(  $request->query->has('billing_firstname')) {

            $name = $request->query->get('billing_firstname');
            $last = $request->query->get('billing_lastname');


            $commande = new Commande();
            $commande->setPrixtotal($total);
            $commande->setEtatcommande("0");
            $commande->setDatecommande(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            foreach ($panier as $item) {
                $panierS = new  LigneCommande();
                $panierS->setIdCommande($commande);
                $panierS->setQuantitydemande($item['quantite_produit']);
                $panierS->setIdProduit($this->getDoctrine()->getRepository(Produit::class)->find($item ['produit']->getId_produit()));
                $em->persist($panierS);
                $em->flush();

            }

            $panier = $session->set('panier', []);

            $this->addFlash('sucess', 'votre commande est confirmée');

            return $this->redirectToRoute('panier1');


        }
        if($total==0){
            return $this->redirectToRoute('panier1');
        }


        return $this-> render('ligne_commande/chekout.html.twig' , [
            'items' => $panier,
            'total'=> $total
        ]);

    }


    /**
     * @Route("/panier/updateProdPanier/{id}/{nb}", name="updateProdPanier")
     * @param $id
     * @param SessionInterface $session
     */

    public function updateProdPanier($id, $nb,Request $req,SessionInterface $session, ProduitRepository $produitRepository) {



        $panier = $session->get('panier',[]);
        $produit=$produitRepository->find($id);
        if(!$produit){
            return $this->redirectToRoute('panier1');
        }
        // dd($panier[$id]['produit']->getId_produit());
        if($req->get('qt')){
            $nb=intval($req->get('qt'));
            if($nb<0)
                $nb=0;
            if (!empty($panier[$id])) {

                $panier[$id]= [
                    'produit' => $produitRepository->find($id),
                    'quantite_produit'=> $nb
                ];

            }else {
                $panier[$id] =[
                    'produit' => $produitRepository->find($id),
                    'quantite_produit'=>$nb
                ];
            }
            $session -> set('panier',$panier);
            return $this->redirectToRoute('panier1');
        }
        if (!empty($panier[$id])) {
            $newqt=$panier[$id]['quantite_produit']+intval($nb);
            if($newqt<0)
                $newqt=0;

            $panier[$id]= [
                'produit' => $produitRepository->find($id),
                'quantite_produit'=> $newqt
            ];

        }else {
            $newqt=intval($nb);
            if($newqt<0)
                $newqt=0;
            $panier[$id] =[
                'produit' => $produitRepository->find($id),
                'quantite_produit'=>$newqt
            ];
        }

        $session -> set('panier',$panier);

        return $this->redirectToRoute('panier1');

    }


    /**
     * @Route("/clearpanier", name="emptypanier")
     * @param SessionInterface $session
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    public function clear(SessionInterface $session): Response
    {
        $panier = $session->set('panier',[]);


        return $this->redirectToRoute('panier1');
    }

    /**
     * @Route("/panier/supprimerProd/{id}", name="panier_supprime")
     */

    public function supprimerProd($id, SessionInterface $session) {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier',$panier);

        return $this->redirectToRoute("panier1");

    }

    /**
     * @Route("/delete/{id}", name="lignecommande_delete", methods={"POST"})
     *
     */
    public function delete(Request $request, LigneCommande $ligneCommande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneCommande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ligneCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lignecommande', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/StatCommande", name="StatCommande")
     */
    public function StatCommande(): Response
    {

        $commandes = $this->getDoctrine()->getRepository(LigneCommande::class)->findAll();
        $quantitydemande= [];
        $id_produit = [];
        foreach ($commandes as $commande) {
            $quantitydemande[] = $commande->getQuantitydemande();
            $id_produit [] = $commande->getIdProduit()->getNom();
        }
        return $this->render('ligne_commande/stattable.html.twig', [
            'quantitydemande' => json_encode($quantitydemande),
            'id_produit' => json_encode($id_produit)
        ]);
    }

    /**
     * @Route("/admin/listcom", name="commande_liste", methods={"GET"})
     */
    public  function  listcom (SessionInterface $session, ProduitRepository $produitRepository):Response{


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        $dompdf = new Dompdf($pdfOptions);

        $panier = $session->get('panier',[]);
        $total= 0;

        foreach($panier as $item){
            $totalitem = $item ['produit'] -> getPrixProduit() * $item['quantite_produit'];
            $total += $totalitem;
        }
        $html =  $this-> render('ligne_commande/listcomm.html.twig' , [
            'items' => $panier,
            'total'=> $total
        ]);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');


        // Rendre le HTML au format PDF
        $dompdf->render();

        // Sortie du PDF généré dans le navigateur (téléchargement forcé)
        $dompdf->stream("facture.pdf", [
            "Attachment" => true
        ]);

    }



    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function s (Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(LigneCommande::class)->findEntitiesByString($requestString);

        if(!$entities) {
            $result['LigneCommande']['error'] = "aucune entitee a ete trouvee";
        } else {
            $result['LigneCommande'] = $this->getRealEntities($entities);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getIdProduit()->getNom()] = [$entity->getIdCommande(),$entity->getQuantitydemande()];
        }

        return $realEntities;
    }


    /**
     * @Route("/list/{id}",name="list")
     */
    public function sh (LigneCommande $ligneCommande): Response
    {
        return $this->render('ligne_commande/list.html.twig', [
            'ligneCommande' => $ligneCommande,
        ]);
    }



    /***************************************mobile********************************************/
    /**
     * @Route("/addprod", name="addprod")
     * @Method("POST")
     */

    public function ajouterProd(Request $request, NormalizerInterface  $normalizer)

    {
        $panier = new LigneCommande();
        $commande= new Commande();
        $quantitydemande = $request->query->get("quantitydemande");
        $em = $this->getDoctrine()->getManager();
        $total=$panier->setIdProduit($this->getDoctrine()->getRepository(Produit::class)->findOneBy(['nom'=>'cheese cake']))->getIdProduit()->getPrixProduit()*$quantitydemande;
        $produit=$panier->setIdProduit($this->getDoctrine()->getRepository(Produit::class)->findOneBy(['nom'=>'cheese cake']))->getIdProduit();

        $commande = new Commande();
        $commande->setPrixtotal($total);
        $commande->setEtatcommande("0");
        $commande->setDatecommande(new \DateTime('now'));
        $em->persist($commande);
        $em->flush();

        $panier->setIdProduit($produit);
        $panier->setIdCommande($commande);
        $panier->setQuantitydemande($quantitydemande);
            $em->persist($panier);
            $em->flush();


        $jsonContent =$normalizer->normalize($panier,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/clearmob", name="emptymob")
     *  @Method("DELETE")
     */

    public function deletePanMob(Request $request) {

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $panier = $em->getRepository(LigneCommande::class)->find($id);
        if($panier!=null ) {
            $em->remove($panier);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Panier a ete vider avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id panier invalide.");
    }
    /**
     * @Route("/updatepanmob", name="updatepan")
     *  @Method("PUT")
     */
    public function modifierQt(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $panier = $this->getDoctrine()->getManager()
            ->getRepository(LigneCommande::class)
            ->find($request->get("id"));

        $panier->setQuantityDemande($request->get("quantitydemande"));

        $em->persist($panier);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($panier);
        return new JsonResponse("panier a ete modifiee avec success.");

    }

    /******* MOBILE PANIER *******/


    /**
     * @Route("/affmobPanier", name="affmobPanierbyid")
     */
    public function affmobPanier(SessionInterface $session,Request $request,NormalizerInterface $normalizer,ProduitRepository $produitRepository)
    {
        $panier=$this->getDoctrine()->getRepository(Panier::class)->findAll();

        $jsonContent = $normalizer->normalize($panier,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/paniermobile/new", name="ajouterpanier")
     */
    public function addmobpanier(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $total = 0;

        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($request->get('id_produit'));
        $Panierr = $this->getDoctrine()->getRepository(Panier::class)->findOneBy([
            'produit' => $produit->getId_produit()
        ]);
        if (!empty($Panierr)) {
            if($produit->getQuantiteProduit()>$Panierr->getQuantite()){

                $Panierr->setQuantite( $Panierr->getQuantite()+1);
                $total += $produit->getPrixProduit() * $Panierr->getQuantite();

                $Panierr->setTotal( $total);

            }else{
                //afficher erreur
            }

            //dump($panier[$id]).die();
        } else {
            $Panierr = new Panier();
            $Panierr->setProduit($produit);
            $Panierr->setQuantite( 1);
            $total += $produit->getPrixProduit() * $Panierr->getQuantite();
            $Panierr->setTotal( $total);
            $em->persist($Panierr);

        }
        $em->flush();
        $jsonContent = $normalizer->normalize($Panierr,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/paniermob/edit", name="editmobPanier")
     */
    public function editmobPanier(Request $request,NormalizerInterface $normalizer)
    {   $em=$this->getDoctrine()->getManager();
        $Panierr = $em->getRepository(Panier::class)->findOneBy([
            'produit' => $request->get('id_produit')
        ]);
        $Panierr->setQuantite($request->get('quantity'));
        $Panierr->setTotal($request->get('total'));
        $em->flush();
        $jsonContent = $normalizer->normalize($Panierr,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/Panier/del", name="deletpanier")
     */
    public function delmobPanier(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $Panier = $this->getDoctrine()->getRepository(Panier::class)->findOneBy([
            'produit' => $request->get('id_produit')
        ]);
        $em->remove($Panier);
        $em->flush();
        $jsonContent = $normalizer->normalize($Panier   ,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/commande/new", name="ajoutercommande")
     */
    public function addCom(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();

            $Panierr = new Commande();
            $Panierr->setDatecommande(new \DateTime('now'));
            $Panierr->setEtatcommande( 0);

            $Panierr->setPrixtotal( $request->get('total'));
            $em->persist($Panierr);


        $em->flush();
        $jsonContent = $normalizer->normalize($Panierr,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @Route("/affmobCommande", name="affmob")
     */
    public function affmobCom(SessionInterface $session,Request $request,NormalizerInterface $normalizer)
    {
        $com=$this->getDoctrine()->getRepository(Commande::class)->findAll();

        $jsonContent = $normalizer->normalize($com,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }


    /**
     * @Route("Commande/del", name="Commandedel")
     */
    public function deletecMob(Request $request,NormalizerInterface $normalizer) {

        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository(Commande::class)->find($id);

            $em->remove($com);
            $em->flush();
            $jsonContent = $normalizer->normalize($com,'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));


    }
}