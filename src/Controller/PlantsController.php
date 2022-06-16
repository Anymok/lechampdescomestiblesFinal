<?php
namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierPlants;
use App\Entity\Plants;
use App\Entity\PlantSearch;
use App\Entity\User;
use App\Form\PlantSearchType;
use App\Repository\PanierPlantsRepository;
use App\Repository\PlantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Param;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping as ORM;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class PlantsController extends AbstractController
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
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     *  @Route("/catalogue", name="plant.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new PlantSearch();
        $form = $this->createForm(PlantSearchType::class, $search);
        $form->handleRequest($request);

        $plants = $paginator->paginate(
            $this->repository->FindAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('plant/index.html.twig', [
            'current_menu' => 'plants',
            'plants' => $plants,
            'form' => $form->createView(),
            'ref' => $request->query->get('ref'),
            'title' => $request->query->get('title'),
            'color' => $request->query->get('color'),
            'type' => $request->query->get('type'),
            'maxPrice' => $request->query->get('maxPrice')

        ]);
    }

    /**
     *  @Route("/catalogue/{slug}-{id}", name="catalogue.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Plants $plants, string $slug) : Response
    {
        if($plants->getSlug() !== $slug) {
            return $this->redirectToRoute('catalogue.show', [ 
                'id' => $plants->getId(),
                'slug' => $plants->getSlug(),
            ], 301);
        }
        return $this->render('plant/show.html.twig', [
            'plants' => $plants,
            'current_menu' => 'catalogue',
            'image' => $plants->getPictures(),
            'pic' => $plants->getPicture(),
            'count' => $plants->getPictures()->count()
        ]);
    }


}
