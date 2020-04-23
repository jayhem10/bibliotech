<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;


use App\Entity\UpdatePassword;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;




class SecurityController extends AbstractController
{

/**
 * @Route("/inscription", name="security_registration")
 */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();

        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);


            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',[
            'form'=> $form->createView()
        ]);

    }


/**
 * @Route("/connexion", name="security_login")
 */
    public function login(AuthenticationUtils $utils){

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();



    return $this->render('security/login.html.twig', [
        'hasError' => $error !== null,
        'username' => $username
    ]);


    }


/**
 * @Route("/deconnexion", name="security_logout")
 */
public function logout(){

    

    }

/**
 * Permet d'afficher et de traiter le formulaire de modification du profil
 * 
 * @Route("/account/profile", name="account_profile")
 * 
 * @IsGranted("ROLE_USER")
 * 
 */
public function profile(Request $request, EntityManagerInterface $manager){

    $user = $this->getUser();

    $form = $this->createForm(AccountType::class , $user );

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){


        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Les données ont été modifiées avec succès !"
        );

        return $this->redirectToRoute('account');
    }

    return $this->render('security/profile.html.twig', [
        'form'=> $form->createView()
    ]);

        
    }


/**
 * Permet d'afficher et de traiter le formulaire de modification du mot de passe
 * 
 * @Route("/account/password-update", name="account_password")
 * @IsGranted("ROLE_USER")
 * @return Response
 * 
 */
public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){

    $user = $this->getUser();

    $passwordUpdate = new UpdatePassword();

    $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){
        // 1. Vérifier que le oldPassword est le même que celui en base de données
        if(!password_verify($passwordUpdate->getOldPAssword(), $user->getPassword())){
            // Gérer l'erreur
        }
        else {
            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->encodePassword($user, $newPassword);

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Le mot de passe a été modifié avec succès !"
            );

            return $this->redirectToRoute('account');
        }

    }


    return $this->render('security/password.html.twig', [
        'form' => $form->createView()
    ]);
        
    }


    // MOT DE PASSE  OUBLIE
    /**
     * @Route("/forgottenPassword", name="app_forgotten_password")
     */
    public function forgottenPassword( Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator): Response
    {


        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('app_forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_forgotten_password');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('noble.jka@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour, voici le token pour reseter votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute('app_forgotten_password');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

        /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {


        if ($request->isMethod('POST')) {
            $manager = $this->getDoctrine()->getManager();

            $user = $manager->getRepository(User::class)->findOneBy(['reset_token' => $token]);
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('security_login');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $manager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('security_login');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }


}
