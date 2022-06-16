<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandePlants;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Plants;
use App\Form\PanierType;
use App\Entity\PanierPlants;
use App\Repository\PanierRepository;
use App\Repository\PlantsRepository;
use App\Repository\PanierPlantsRepository;
use App\Repository\UserProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier", methods={"GET"})
     */
    public function index(PanierRepository $panierRepository, PlantsRepository $PlantsRepository, PanierPlantsRepository $PanierPlantsRepository,  UserInterface $user, UserProfilRepository $userProfilRepository): Response
    {
        $PanierPlants = $PanierPlantsRepository->FindByPanier($user->getPanier());
        $count = 0;
        $Mtotal = 0;
        while ($count != count($PanierPlants))
        {
            $Mtotal = $Mtotal + ($PanierPlants[$count]->getPlants()->getPrice() * $PanierPlants[$count]->getQt() );

            $count += 1;
            
        }

        
        return $this->render('panier/index.html.twig', [
            'PanierPlants' => $PanierPlantsRepository->FindByPanier($user->getPanier()),
            'UserProfil' => $userProfilRepository->FindAdr($user),
            'Mtotal' => $Mtotal
        ]);
    }



    /**
     * @Route("/{id}", name="panier.delete", methods={"DELETE"})
     */
    public function delete(Request $request, PanierPlantsRepository $PanierPlantsRepository, Plants $plants, UserInterface $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plants->getID(), $request->request->get('_token'))) {
            $PanierPlants = $PanierPlantsRepository->FindByPlants($plants, $user->getPanier());
            $PanierPlantsRepository->removeByPlants($plants->getID());
        }

        return $this->redirectToRoute('panier', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/confirm", name="panier.confirm", methods={"GET"})
     */
    public function confirm(UserInterface $user, UserProfilRepository $userProfilRepository, PanierPlantsRepository $panierPlantsRepository)
    {
        $PanierPlants = $panierPlantsRepository->FindByPanier($user->getPanier());
            $count = 0;
            $Mtotal = 0;
            $NBplante = 0;
            while ($count != count($PanierPlants))
            {
                $Mtotal = $Mtotal + ($PanierPlants[$count]->getPlants()->getPrice() * $PanierPlants[$count]->getQt() );
                $NBplante = $NBplante + ($PanierPlants[$count]->getQt());
                $count += 1;
                
            }
        return $this->render('panier/confirm.html.twig', [
            'UserProfil' => $userProfilRepository->FindAdr($user),
            'Mtotal' => $Mtotal,
            'NBarticle' => $count,
            'NBplante' => $NBplante

        ]);
    }

    /**
     * @Route("/confirm/add", name="panier.confirm.add", methods={"CREATE"})
     */
    public function create(Request $request,UserInterface $user, PanierPlantsRepository $panierPlantsRepository)
    {

        if($this->isCsrfTokenValid('create' . $user->getId(), $request->request->Get('_token'))) {
            $PanierPlants = $panierPlantsRepository->FindByPanier($user->getPanier());
            $count = 0;
            $Mtotal = 0;
            while ($count != count($PanierPlants))
            {
                $Mtotal = $Mtotal + ($PanierPlants[$count]->getPlants()->getPrice() * $PanierPlants[$count]->getQt() );
                $count += 1;
            }

            if ($PanierPlants != NULL)
            {
            $commande = New Commande();
            $commande->setUser($user);
            $commande->setNom($request->request->get('last_name'));
            $commande->setPrenom($request->request->get('first_name'));
            $commande->setMail($request->request->get('mail'));
            $commande->setTel($request->request->get('phone'));
            $commande->setVille($request->request->get('city'));
            $commande->setPays($request->request->get('country'));
            $commande->setCP($request->request->get('CP'));
            $commande->setRue($request->request->get('street'));
            $commande->setStatus(false);
            $commande->setMTotal($Mtotal);

            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();


            

            
            $count = 0;
            while ($count != count($PanierPlants))
            {
                $CommandePlants = new CommandePlants();
                $CommandePlants->setCommande($commande);
                $CommandePlants->setQuantity($PanierPlants[$count]->getQt());
                $CommandePlants->setTitle($PanierPlants[$count]->getPlants()->getTitle());
                $CommandePlants->setPrice($PanierPlants[$count]->getPlants()->getPrice());
                $CommandePlants->setPlid($PanierPlants[$count]->getPlants()->getId());

                $em->persist($CommandePlants);
                $em->flush();

                $count += 1;
            }

            $panierPlantsRepository->removeByUser($user);
            }
        }
        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/catalogue/{id}", name="catalogue.add", methods={"ADD"})
     * @return Response
     */
    public function add(Plants $plants, Request $request, Security $security, PanierPlantsRepository $panierPlantsRepository) : Response
    {

        
        if($this->isCsrfTokenValid('add' . $plants->getId(), $request->request->Get('_token'))) {
            $em = $this->getDoctrine()->getManager();
           
            $user = New User();
            $user = $security->getUser();

            $PanierPlants = new PanierPlants();

            $result = $panierPlantsRepository->FindByPlants($plants, $user->getPanier());
           
            if ($result == NULL)
            {
                
                $PanierPlants->setPlants($plants);
                $PanierPlants->setPanier($user->getPanier());
                $PanierPlants->setQt($request->request->Get('_quantity'));
                $em->persist($PanierPlants);
            
           
            }else{
                $PanierPlants = $panierPlantsRepository->FindByPlants($plants, $user->getPanier());
                if (($PanierPlants[0]->getQt() + $request->request->Get('_quantity') <= ($plants->getQuantity())))
                {
                    $PanierPlants = $panierPlantsRepository->FindByPlants($plants, $user->getPanier());
                    $PanierPlants[0]->setQt($PanierPlants[0]->getQt() + $request->request->Get('_quantity'));
                    $em->persist($PanierPlants[0]);
                }
                else
                {
                    $PanierPlants = $panierPlantsRepository->FindByPlants($plants, $user->getPanier());
                    $PanierPlants[0]->setQt($plants->getQuantity());
                    $em->persist($PanierPlants[0]);
                }
                
                
            }
            
            
                
            
            
        }
            $em->flush();
            
    
           
           
            
        

        return $this->redirectToRoute('panier');
        
       
    }
}
