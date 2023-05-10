<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\LigneCommande;
use App\Form\SearchcommandeType;
use App\Repository\LigneCommandeRepository;
use App\Form\CommandeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{

    
    #[Route('/', name: 'commande_index')]
    public function index(CommandeRepository $commandeRepository ,Request $request,PaginatorInterface $paginator): Response
    {
        $commandes = $paginator->paginate(
        // Doctrine Query, not results
            $commandeRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

     
     
    #[Route('/{id_com}', name: 'commande_delete', methods:['POST'] )]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCom(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }
        $this->addFlash('clear', 'Une commande est supprimée');
        return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/admin/listcom", name="commande_list", methods={"GET"})
     */
    /*public  function  listcom (CommandeRepository  $commandeRepository):Request{
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $commandes= $commandeRepository->findAll();
        $html = $this->renderView('commande/listecom.html.twig',['commandes'=>$commandes,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        // Rendre le HTML au format PDF
        $dompdf->render();
        // Sortie du PDF généré dans le navigateur (téléchargement forcé)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }*/

    // pdf pour la liste des commandes 
    public function listcom(CommandeRepository $commandeRepository): Response
{
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($pdfOptions);
    $commandes = $commandeRepository->findAll();
    $html = $this->renderView('commande/listecom.html.twig', ['commandes' => $commandes]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $output = $dompdf->output();
    $filename = 'mypdf.pdf';
    $tempFilePath = tempnam(sys_get_temp_dir(), $filename);

    file_put_contents($tempFilePath, $output);

    $response = new BinaryFileResponse($tempFilePath);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $filename);

    return $response;
}

// Tri des prix des commandes en ordre croissannt 
    #[Route('/admin/sortprix', name: 'sortprix')]
    public function triprix(): Response
{
    $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy([], ['prixtotal' => 'ASC']);
    return $this->render('commande/SortedPrix.html.twig', [
        'controller_name' => 'CommandeController',
        'prixsorted' => $commandes,
    ]);
}

}

?>