<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;

use App\Form\UserType; 

use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{   
    #[Route('/afterlogin', name: 'afterlogin')]
    public function test(UserRepository $repo): Response
    {
        

        return $this->render('afterlogin.html.twig');
    }
    
    #[Route('/baseBack', name: 'baseBack')]
    public function baseBack()
    {
        
        
        return $this->render('baseBack.html.twig');
    }

    #[Route('/User/Read', name: 'Read_User')]
    public function ReadUser(UserRepository $repo): Response
    {
        $Users = $repo->findAll();
        
        return $this->render('User/ReadUser.html.twig', ["Users"=>$Users]);
    }

    #[Route('/User/Create', name: 'Create_User')]
    public function CreateUser(ManagerRegistry $doctrine, Request $req,UserPasswordEncoderInterface $encoder): Response 
    {
        $em = $doctrine->getManager();
        $User = new User();
        $form = $this->createForm(UserType::class,$User);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {   
            $User->setPassword($encoder->encodePassword($User, $form->get('password')->getData()));
            // lena try catch
            $User->setRoles(["ROLE_USER"]);
            $em->persist($User);
            $em->flush();
            return $this->redirectToRoute('afterlogin');
        }
        return $this->renderForm('User/CreateUser.html.twig',['form'=>$form]);
    
    }

    #[Route('/User/Delete/{id}', name: 'Delete_User')]
    public function DeleteUser(ManagerRegistry $doctrine, $id): Response 
    {
        $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(User::class);
        $User= $repo->find($id);
        $em->remove($User);
        $em->flush();

        return $this->redirectToRoute('Read_User');
    }

    #[Route('/User/Update/{id}', name: 'Update_User')]
    public function UpdateUser(ManagerRegistry $doctrine, $id, Request $req,UserPasswordEncoderInterface $encoder): Response 
    {
        $em = $doctrine->getManager();
        $User = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class,$User);
        $form->handleRequest($req); 

        if($form->isSubmitted())
        {   
            $User->setPassword($encoder->encodePassword($User, $form->get('password')->getData()));
            $em->persist($User);
            $em->flush();
            return $this->redirectToRoute('Read_User');
        }

        return $this->renderForm('User/CreateUser.html.twig',['form'=>$form]);

    }

}
?>