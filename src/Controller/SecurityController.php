<?php
namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Entity\UserProfil;
use App\Form\UserProfilType;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername= $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid())
    {
        $this->em = $this->getDoctrine()->getManager();

        $UserProfil = new UserProfil();
        $this->em->flush();

        $panier = new Panier();

        $pass = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user, $pass));
        $user->setRole('ROLE_ADMIN');
        $user->setUserProfil($UserProfil);
        $user->setPanier($panier);
        $this->em->persist($user);
        $this->em->flush();
       
        
       

        $this->addFlash('success', 'Compte crée avec succès');
        return $this->redirectToRoute('login');
    }


        return $this->render('security/register.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


}

