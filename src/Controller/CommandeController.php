<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Commande;
use App\Repository\CommandePlantsRepository;
use App\Repository\CommandeRepository;
use App\Repository\PanierPlantsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeController extends AbstractController
{

    /**
     * @Route("/commande", name="commande")
     */
    public function index(CommandeRepository $commandeRepository, UserInterface $user): Response
    {
        return $this->render('commande/index.html.twig', [
            'commande' => $commandeRepository->FindByUser($user)
        ]);
    }

    /**
     * @Route("/commande/pdf", name="commande.pdf")
     */
    public function pdfDownload(Request $request, CommandeRepository $commandeRepository, CommandePlantsRepository $commandePlantsRepository)
    {
     

        if ($this->isCsrfTokenValid('download'.$request->request->get('_id'), $request->request->get('_token'))) {

         
            $commande = $commandeRepository->FindCommandePDF($request->request->get('_id'));
            
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('commande/pdf.html.twig', [
                'commande' => $commandeRepository->FindCommandePDF($request->request->get('_id')),
                'plants' => $commandePlantsRepository->FindPlantsPDF($request->request->get('_id'))
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser (force download)
            $dompdf->stream("facture.pdf", [
                "Attachment" => true
            ]);


        }
    }
        
    }
    
