<?php

namespace App\Controller;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LigneCommandeRepository;
use App\Entity\LigneCommande;
use App\Entity\Commande;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Panier;
use App\Repository\CommandeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\name;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $totalitem = $item ['produit'] -> getPrix() * $item['nbr_Prods'];
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
            $totalitem = $item ['produit'] -> getPrix() * $item['nbr_Prods'];
            $total += $totalitem;
        }


        if(  $request->query->has('billing_firstname')) {

            $name = $request->query->get('billing_firstname');
            $last = $request->query->get('billing_lastname');


            $commande = new Commande();
            $commande->setPrixtotal($total);
            $commande->setEtatCom("0");
            $commande->setDateCom(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            foreach ($panier as $item) {
                $panierS = new  LigneCommande();
                $panierS->setIdCommande($commande);
                $panierS->setQteDem($item['nbr_Prods']);
                $panierS->setId($this->getDoctrine()->getRepository(Produit::class)->find($item ['produit']->getId()));
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
     * @param $id_l_p
     * @param SessionInterface $session
     */

     public function updateProdPanier($id_l_p, $nb,Request $req,SessionInterface $session, ProduitRepository $produitRepository) {

        $panier = $session->get('panier',[]);
        $produit=$produitRepository->find($id_l_p);
        if(!$produit){
            return $this->redirectToRoute('panier1');
        }
        // dd($panier[$id]['produit']->getId_produit());
        if($req->get('qt')){
            $nb=intval($req->get('qt'));
            if($nb<0)
                $nb=0;
            if (!empty($panier[$id_l_p])) {

                $panier[$id_l_p]= [
                    'produit' => $produitRepository->find($id_l_p),
                    'nbr_Prods'=> $nb
                ];

            }else {
                $panier[$id_l_p] =[
                    'produit' => $produitRepository->find($id_l_p),
                    'nbr_Prods'=>$nb
                ];
            }
            $session -> set('panier',$panier);
            return $this->redirectToRoute('panier1');
        }
        if (!empty($panier[$id_l_p])) {
            $newqt=$panier[$id_l_p]['nbr_Prods']+intval($nb);
            if($newqt<0)
                $newqt=0;

            $panier[$id_l_p]= [
                'produit' => $produitRepository->find($id_l_p),
                'nbr_Prods'=> $newqt
            ];

        }else {
            $newqt=intval($nb);
            if($newqt<0)
                $newqt=0;
            $panier[$id_l_p] =[
                'produit' => $produitRepository->find($id_l_p),
                'nbr_Prods'=>$newqt
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

     public function supprimerProd($id_l_p, SessionInterface $session) {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id_l_p])) {
            unset($panier[$id_l_p]);
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
        if ($this->isCsrfTokenValid('delete'.$ligneCommande->getId_L_P(), $request->request->get('_token'))) {
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
        $qte_dem= [];
        $id = [];
        foreach ($commandes as $commande) {
            $qte_dem[] = $commande->getQteDem();
            $id [] = $commande->getId()->getNomProd();
        }
        return $this->render('ligne_commande/stattable.html.twig', [
            'qte_dem' => json_encode($qte_dem),
            'id' => json_encode($id)
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
            $totalitem = $item ['produit'] -> getPrix() * $item['nbr_Prods'];
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
            $realEntities[$entity->getId()->getNomProd()] = [$entity->getIdCommande(),$entity->getQteDem()];
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

}
?>