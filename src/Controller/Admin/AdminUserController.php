<?php
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @var PlantsRepository
     */
    private $repository;
     /**
     * @var ManagerRegistry
     */
    private $em;

    public function __construct(UserRepository $repository, ManagerRegistry $em)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     *
     * @return Response
     */
    public function index() : Response
    {
        $user = $this->repository->findAll();
        return $this->render('admin/user/index.html.twig', [
            'user' => $user,
            'current_menu' => 'guser'
        ]);
    }



    /** 
     * @Route("/admin/user/{id}", name="admin.user.delete", methods="DELETE")
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function delete(User $user, Request $request) : Response
    {
        
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
         $this->em = $this->getDoctrine()->getManager();
         $this->em->remove($user);
         $this->em->flush();
         $this->addFlash('success', 'Compte supprimé.');
        }
        return $this->redirectToRoute('admin.user.index');
    }


    
    /** 
     * @Route("/admin/user/{id}", name="admin.user.reverse", methods="REVERSE")
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function reverse(User $user, Request $request) : Response
    {
        
        if ($this->isCsrfTokenValid('reverse' . $user->getId(), $request->get('_token'))) {
         $this->em = $this->getDoctrine()->getManager();
         
         if($user->getRole() == "ROLE_USER")
         {
            $user->setRole("ROLE_ADMIN");
         }
         else if ($user->getRole() == "ROLE_ADMIN")
         {
            $user->setRole("ROLE_USER");
         }
         $this->em->persist($user);
         $this->em->flush();
         $this->addFlash('success', 'Compte modifié.');
        }
        return $this->redirectToRoute('admin.user.index');
    }


}