<?php
namespace App\Controller\Admin;

use App\Entity\Plants;
use App\Form\PlantsType;
use Cocur\Slugify\Slugify;
use App\Repository\PlantsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminPlantController extends AbstractController
{
    /**
     * @var PlantsRepository
     */
    private $repository;
     /**
     * @var ManagerRegistry
     */
    private $em;

    public function __construct(PlantsRepository $repository, ManagerRegistry $em)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @Route("/admin", name="admin.plants.index")
     *
     * @return Response
     */
    public function index() : Response
    {
        $plants = $this->repository->findAll();
        return $this->render('admin/plants/index.html.twig', [
            'plants' => $plants,
            'current_menu' => 'gplant'
        ]);
    }

     /**
    * @Route("/admin/plants/create", name="admin.plants.new")
     */
    public function new(Request $request)
    {
        $plants = new Plants();
        $form = $this->createForm(PlantsType::class, $plants);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em = $this->getDoctrine()->getManager();
            $this->em->persist($plants);
            $this->em->flush();
            $this->addFlash('success', 'Bien crée avec succès');
            return $this->redirectToRoute('admin.plants.index');
        }

        return $this->render('admin/plants/new.html.twig', [
            'plants' => $plants,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("admin/plants/{id}", name="admin.plants.edit", methods="GET|POST")
     *
     * @return Response
     */
    public function edit(Plants $plants, Request $request) : Response
    {
        $form = $this->createForm(PlantsType::class, $plants);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
           
            $this->em = $this->getDoctrine()->getManager();
            $this->em->persist($plants);
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.plants.index');
        }

        return $this->render('admin/plants/edit.html.twig', [
            'plants' => $plants,
            'form' => $form->createView()
        ]);
    }

 /**
     * @Route("/admin/plants/{id}", name="admin.plants.delete", methods="DELETE")
     *
     * @param Plants $plants
     * @param Request $request
     * @return Response
     */
    public function delete(Plants $plants, Request $request, PlantsRepository $plantsRepository) : Response
    {
        
        if ($this->isCsrfTokenValid('delete' . $plants->getId(), $request->get('_token'))) {
         $this->em = $this->getDoctrine()->getManager();
         $plantsRepository->deletePanierByPlants($plants);
         $count = 0;
         $tab = $plants->getPictures();
         while ($count != $plants->getPictures()->count())
         {
            $this->em->remove($tab[$count]);
            $count = $count + 1;
         }
         $this->em->remove($plants);
         $this->em->flush();
         $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.plants.index');
    }


}