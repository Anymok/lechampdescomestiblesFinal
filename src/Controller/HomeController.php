<?php
namespace App\Controller;

use App\Repository\PlantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     *  @Route("/", name="home")
     * @return Response
     */
    public function index(PlantsRepository $repository): Response
    {
        $plant = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'plants' => $plant
        ]);
    }
}
