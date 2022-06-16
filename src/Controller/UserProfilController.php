<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfil;
use App\Form\UserProfilType;
use App\Repository\UserProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @Route("/profil")
 */
class UserProfilController extends AbstractController
{
    /**
     * @Route("/", name="profil.index")
     */
    public function index(?UserProfil $userProfil, Request $request, UserProfilRepository $userProfilRepository, UserInterface $user): Response
    {
        if(!$userProfil){
            $userProfil = new UserProfil();
            $userProfil = $user->getUserProfil();
        }
    
        $form = $this->createForm(UserProfilType::class, $userProfil);
     
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $userProfilRepository->add($userProfil);
        }
        
        return $this->renderForm('profil/index.html.twig', [
            'form' => $form
        ]);
    }



    /**
     * @Route("/{id}/edit", name="profil.edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserProfil $userProfil, UserProfilRepository $userProfilRepository): Response
    {
        $form = $this->createForm(UserProfilType::class, $userProfil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfilRepository->add($userProfil);
            return $this->redirectToRoute('profil.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/edit.html.twig', [
            'UserProfil' => $userProfil,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{id}/modadr", name="profil.modadr", methods={"GET", "POST"})
     */
    public function modadr(UserProfil $userProfil, UserProfilRepository $userProfilRepository): Response
    {
   
            $userProfilRepository->add($userProfil);

        

        return $this->redirectToRoute('profil.+', [], Response::HTTP_SEE_OTHER);
    }

   

    

}
