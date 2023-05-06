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
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Entity\Notification;

use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Controller\Serializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\SendMailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Transport\Serialization\Serializer as SerializationSerializer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NotificationRepository;




class UserController extends AbstractController
{   
    #[Route('/afterlogin', name: 'afterlogin')]
    public function test(UserRepository $repo,TexterInterface $texter, Security $security): Response
    {
        
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('Read_User');
        }
        if ($security->isGranted('ROLE_USER')) {
            return $this->render('afterlogin.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }


    
    #[Route('/baseBack', name: 'baseBack')]
    public function baseBack()
    {
        
        
        return $this->render('baseBack.html.twig');
    }

    #[Route('/User/Read', name: 'Read_User')]
    public function ReadUser(UserRepository $repo, Security $security): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $Users = $repo->findAll();
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->render('User/ReadUser.html.twig', [
                'Users' => $Users,
                'notifications' => $notifications,
            ]);
        }
        if ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('afterlogin');
        }
        return $this->redirectToRoute('app_login');
    }


    // [Route('/home', name: 'app_home')]
    // public function index(Security $security): Response
    // {   
    //     if ($security->isGranted('ROLE_ADMIN')) {
    //         return $this->render('admin/dashboard.html.twig');
    //     }
    //     if ($security->isGranted('ROLE_USER')) {
    //         return $this->redirectToRoute('app_user_dashboard');
    //     } 
    //     return $this->render('home/index.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }



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


    #[Route('/add', name: 'add_user', methods:['POST'])]
    public function adduser(Request $request,SerializerInterface $serializer,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): Response 
    {
        
        $content=$request->getContent();
        $data=$serializer->deserialize($content, User::class, 'json');
        $data->setPassword($encoder->encodePassword($data, $data->getPassword()));
        $em->persist($data);
        $em->flush();
        return new Response('user added successfully');
    }


    #[Route('/notif/Delete/{id}', name: 'delete_notif')]
    public function deleteNotification(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, $id): Response
    {   $em= $doctrine->getManager();
        $repo= $doctrine->getRepository(Notification::class);
        $Notification= $repo->find($id);
        $em->remove($Notification);
        $entityManager->flush();
        
     
        return $this->redirectToRoute('Read_User');
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

    #[Route('/User/Status/{id}', name: 'Status')]
    public function DisableOrEnableUser(ManagerRegistry $doctrine, $id): Response
    {
        $em = $doctrine->getManager();
        $repo = $doctrine->getRepository(User::class);
        $User = $repo->find($id);
    
        if ($User->getStatus() === 'enabled') {
            $User->setStatus('disabled');
        } elseif ($User->getStatus() === 'disabled') {
            $User->setStatus('enabled');
        }
    
        $em->persist($User);
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
    #[Route('/list', name: 'list')]
    public function getUsers(UserRepository $repo,SerializerInterface $serializerInterface): Response 
    {
        $Users =$repo->findAll();
        // $json=$serializerInterface.serialize($Users,'json',['gourps'=>'students']); 
        // $jsonEncoder = new JsonEncoder();
        // $serializer = new Serializer([$normalizer], [$jsonEncoder]);
        $json = $serializerInterface->serialize($Users, 'json', [
            'groups' => ['User']
        ]);
        
        dump($json);
        die;

    }

    // #[Route('/user/signin', name: 'app_login', methods: ['GET'])]
    // public function signinAction(Request $request, SerializerInterface $serializer): Response
    // {
    //     $username = $request->query->get("username");
    //     $password = $request->query->get("password");
        

    //     $em= $this->getDoctrine()->getManager();
    //     $user = $em->getRepository(User::class)->findOneBy(['username'=>$username]);
    
    //     if ($user) {
    //         if (password_verify($password,$user->getPassword())) {
                
    //             //  $formatted= $serializer->normalize($user);
    //             //  return new JsonResponse($formatted);
    //              $data = $serializer->serialize($user, 'json', ['groups' => 'user']);

    //              return new JsonResponse($data, 200, [], true);
    //         } else {
    //             return new Response ("password not found");
    //         }
    //     } else {
    //         return new Response ("user not found");
    //     }
    // }
    #[Route('/notification', name: 'notification')]
    public function usersList(NotificationRepository $repo): Response
    {   $notifications = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        
        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }


    #[Route('/user/signin', name: 'app_loginn', methods: ['GET'])]
    public function signinAction(Request $request, SerializerInterface $serializer): Response
    {
        $username = $request->query->get("username");
        $password = $request->query->get("password");
    
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
    
        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $data = $serializer->serialize($user, 'json', ['groups' => 'User']);
                return new Response($data, 200, [
                    'Content-Type' => 'application/json'
                ]);
            } else {
                return new Response(json_encode(['message' => 'password not found']), 404, [
                    'Content-Type' => 'application/json'
                ]);
            }
        } else {
            return new Response(json_encode(['message' => 'user not found']), 404, [
                'Content-Type' => 'application/json'
            ]);
        }
    }








    
    #[Route(path: '/forgot', name: 'app_forgot')]
    public function forgottenPassword(
        Request $request,
        UserRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if($user){
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('app_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                
                // On crée les données du mail
                $context = compact('url', 'user');

                // Envoi du mail
                $mail->send(
                    'yessine@gogym.tn',
                    $user->getEmail(),
                    'Réinitialisation de mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('Create_User');
            }
            // $user est null
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('Create_User');
        }

        return $this->render('/security/forgot.html.twig', [
            'requestPassForm' => $form->createView()
        ]);

        
    }
    #[Route(path: '/forgot/{token}', name: 'app_reset')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) : Response
    {   
        $user = $usersRepository->findOneByResetToken($token);
        if($user){
            $form = $this->createForm(ResetPasswordRequestFormType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){   
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
               
                $message = 'User ' . $user->getUsername() . ' has reset their password.';
                $this->sendNotification($message);

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
            
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');


    }
    private function sendNotification(string $message): void
{
    $notification = new Notification();
    $notification->setMessage($message);
    $notification->setCreatedAt(new \DateTimeImmutable());
    $notification->setIsRead(false);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($notification);
    $entityManager->flush();
}

#[Route('/notification/{id}/read', name: 'notification_read')]
public function markNotificationAsRead(Notification $notification, EntityManagerInterface $entityManager): Response
{
    $notification->setIsRead(true);
    $entityManager->flush();

    return $this->redirectToRoute('Read_User'); // Redirect to dashboard or any other page
}
public function index(): Response
{
    $user = $this->getUser();
    return $this->render('user/index.html.twig', [
        'user' => $user,
    ]);
}
private NotificationRepository $notificationRepository;

public function __construct(NotificationRepository $notificationRepository)
{
    $this->notificationRepository = $notificationRepository;
}
}
?>