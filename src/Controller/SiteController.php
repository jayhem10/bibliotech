<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;




use App\Entity\Collection;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Follow;
use App\Repository\CollectionRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Repository\FollowRepository;
use App\Form\ElementType;
use App\Form\FollowType;

class SiteController extends Controller
{


    /**
     * @Route("/collection", name="collection")
     */
    public function index(EntityManagerInterface $manager,  Request $request)
    {

    
        // Je récupère l'ID de l'user connecté pour n'afficher que ses propres éléments de collection
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser()->getId();

        $q = $request->query->get('q');

        // Je crée la requête DQL qui récupère dans la table collection les éléments en rapport avec l'id de l'utilisateur connecté
        $listes = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 

        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $listes,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();          

        return $this->render('site/index.html.twig', [
            'listes' => $listes,
            'pagelistes'=> $pagelistes,
            'cat' => $cat,
          
        ]);
    }

    

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('site/home.html.twig');
    }


    
    // Je place la route new avant la collection id pour éviter que collection/new soit considéré en tant que id et non en tant que route à part

        /**
     * @Route("/collection/new", name="collection_create")
     * @Route("/collection/{id}/edit", name="collection_edit")
     */
    public function form(Collection $element = null, Request $request, EntityManagerInterface $manager){



        if(!$element){

            $element = new Collection();

        }

        // Je veux récupérer l'id de l'user (TEST)
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        

            $form = $this->createForm(ElementType::class, $element);
                            

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $element -> setUser($user);
                $manager->persist($element);
                $manager->flush();

                return $this->redirectToRoute('site_show', ['id'=> $element->getId()]);
            }


        return $this->render('site/create.html.twig',[
            'formCollection' => $form->createView(),
            'editMode' => $element->getId() !== null
            
            
        ]);

    }
    



/**
* @Route("/collection/CD", name="category_cd")
*/
    public function cd(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $cds = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 130 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $cds,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );
    

        return $this->render('site/cd.html.twig' ,[
            'cds'=> $cds,
            'pagecds' => $pagelistes
        ]);
    }

/**
* @Route("/collection/jeux", name="category_jeux")
*/
    public function jeux(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $jeux = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 131 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $jeux,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );
    

        return $this->render('site/jeux.html.twig' ,[
            'jeux'=> $jeux,
            'pagejeux' => $pagelistes
        ]);
    }

/**
* @Route("/collection/livre", name="category_livre")
*/
    public function livre(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $livres = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 132 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $livres,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );

    

        return $this->render('site/livre.html.twig' ,[
            'livres'=> $livres,
            'pagelivres' => $pagelistes
        ]);
    }


/**
*@Route("/collection/bluray", name="category_bluray")
*/
    public function bluRay(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $blurays = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 133 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $blurays,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );
    

        return $this->render('site/bluray.html.twig' ,[
            'blurays'=> $blurays,
            'pageblurays' => $pagelistes
        ]);
    }


/**
* @Route("/collection/dvd", name="category_dvd")
*/
    public function dvd(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $dvds = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 134 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $dvds,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );
    

        return $this->render('site/dvd.html.twig' ,[
            'dvds'=> $dvds,
            'pagedvds' => $pagelistes
        ]);
    }


        /**
     * @Route("/collection/vinyle", name="category_vinyle")
     */
    public function vinyle(EntityManagerInterface $manager, Request $request)
    {
 
        $user = $this->getUser()->getId();
        $q = $request->query->get('q');


        $vinyles = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user AND c.category = 135 AND c.title LIKE '%$q%' ORDER BY c.id DESC")->getResult(); 
        
        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $vinyles,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );

    

        return $this->render('site/vinyle.html.twig' ,[
            'vinyles'=> $vinyles,
            'pagevinyles' => $pagelistes
        ]);
    }


    /**
     * @Route("/collection/{id}", name="site_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Collection::class);
        $element = $repo->find($id);

    

        return $this->render('site/show.html.twig' ,[
            'collection'=> $element
        ]);
    }

/**
*@Route("/collection/delete/{id}", name="collection_delete")
*/
    public function delete($id)
    {
        $repo = $this->getDoctrine()->getManager();
        $element = $repo->getRepository(Collection::class)->find($id);

    

        if( isset($_POST['confirm'])){

            $repo->remove($element);
            $repo->flush();
            return $this->redirectToRoute('collection');
        }

    

        return $this->render('site/delete.html.twig' ,[
            'collection'=> $element
        ]);
    }


/**
*@Route("/others", name="other_users")
*/
public function otherUser(EntityManagerInterface $manager, Request $request)
{

    $s = $request->query->get('s');

    $users = $manager->createQuery("SELECT u FROM App\Entity\User u WHERE u.pseudo LIKE '%$s%' ORDER BY u.pseudo DESC")->getResult(); 


    $pageusers = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
        $users,
        // Definir le parametre de la page
        $request->query->getInt('page', 1),
            // Nombre d'objets par page
        60
    );



    return $this->render('site/otheruser.html.twig' ,[
        'users'=> $pageusers
    ]);
}



/**
* @Route("/others/{id}", name="this_user")
*/
    public function userCollection(Follow $element = null, EntityManagerInterface $manager,  Request $request, $id)
    {

        
        $u = $request->query->get('u');
        
        $userLevel = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE  c.user = $id")->getResult();


       
        $userCollection = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE  c.user = $id AND c.title LIKE '%$u%' ORDER BY c.id DESC")->getResult(); 

        $pagelistes = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $userCollection,
            // Definir le parametre de la page
            $request->query->getInt('page', 1),
                // Nombre d'objets par page
            18
        );

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();   
        
        // Je veux récupérer l'id de l'user (TEST)
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        
        
// BOUTON SUIVRE

        if(!$element){

            $element = new Follow();

        }

        $user = $this->getUser()->getId();

        $repo = $this->getDoctrine()->getRepository(User::class);
        $follow = $repo->find($id);



            $form = $this->createForm(FollowType::class, $element);

            $form->handleRequest($request);

            if(isset($_POST['add'])){
                $element -> setFollower($user)
                          -> setFollowing($follow);

                $manager->persist($element);
                $manager->flush();
                $this->addFlash('follow', 'L\'utilisateur a bien été ajouté');
               
                
                return $this->redirectToRoute('follow');
            }

           

// BOUTON NE PLUS SUIVRE



    $followOk = $manager->createQuery("SELECT f.id FROM App\Entity\Follow f WHERE  f.follower = $user AND f.following = $id")->getOneOrNullResult(); 

    if($followOk != null){

        $repos = $this->getDoctrine()->getManager();
        $element = $repos->getRepository(Follow::class)->find($followOk);

        if( isset($_POST['delete'])){
            
            $repos->remove($element);
            $repos->flush();
            $this->addFlash('unfollow', 'L\'utilisateur a bien été supprimé');

            return $this->redirectToRoute('follow');
        }
    }


         


        return $this->render('site/thisuser.html.twig', [
          'formCollection' => $form->createView(),
          'level' => $userLevel,
          'listes'=> $userCollection,
          'pagelistes' => $pagelistes,
          'cat' => $cat,
          'isFollowed' => $followOk
          
        ]);
    }

        /**
     * @Route("/account", name="account")
     */
    public function account(EntityManagerInterface $manager,  Request $request)
    {

        // Je récupère l'ID de l'user connecté pour n'afficher que ses propres éléments de collection
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser()->getId();


        // Je crée la requête DQL qui récupère dans la table collection les éléments en rapport avec l'id de l'utilisateur connecté
        $listes = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user")->getResult(); 

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();          

        return $this->render('site/account.html.twig', [
          'listes'=> $listes,
          'cat' => $cat,
        ]);
    }

    /**
     * @Route("/account/info", name="account_info")
     */
    public function info(EntityManagerInterface $manager,  Request $request)
    {
-
        // Je récupère l'ID de l'user connecté pour n'afficher que ses propres éléments de collection
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser()->getId();


        // Je crée la requête DQL qui récupère dans la table collection les éléments en rapport avec l'id de l'utilisateur connecté
        $listes = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE c.user = $user")->getResult(); 

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();          

        return $this->render('site/info.html.twig', [
          'listes'=> $listes,
          'site' => "Biblio'tech"
          
        ]);
    }



    /**
     * @Route("/account/follow", name="follow")
     */
     public function follow(EntityManagerInterface $manager,  Request $request)
     {
 
        $user = $this->getUser()->getId();
        

        $s = $request->query->get('s');

        $listes = $manager->createQuery("SELECT f FROM App\Entity\Follow f INNER JOIN App\Entity\User u  WHERE f.following = u.id  AND f.follower = $user AND u.pseudo LIKE '%$s%'")->getResult(); 

        

         return $this->render('site/follow.html.twig', [

            'users'=> $listes,
            

         ]);
     }


}
