<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;


use App\Entity\UpdatePassword;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




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
 * 
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



}
