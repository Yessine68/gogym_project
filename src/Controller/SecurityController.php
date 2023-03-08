<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    // #[Route(path: '/login', name: 'app_login')]
    // public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    // {
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     $lastUsername = $authenticationUtils->getLastUsername();
    
    //     // Check if user exists and is not disabled
    //     $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $lastUsername]);
    //     if ($user && $user->getStatus() === 'disabled') {
    //         $this->addFlash('error', 'Your account has been disabled. Please contact an administrator.');
    //         return $this->redirectToRoute('app_login');
    //     } else { return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);}
    
       
    // }
}
