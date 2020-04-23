<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Follow;
use App\Entity\Category;
use App\Entity\Wishlist;
use App\Form\FollowType;
use App\Form\ElementType;
use App\Entity\Collection;




use App\Form\WishlistType;
use App\Repository\UserRepository;
use App\Repository\FollowRepository;
use App\Repository\CategoryRepository;
use App\Repository\CollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SiteController extends Controller
{


    /**
     * @Route("/collection", name="collection")
     * @IsGranted("ROLE_USER")
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
     * 
     */
    public function home()
    {
        return $this->render('site/home.html.twig');
    }


    

     /**
     * @Route("/collection/new", name="collection_create")
     * @IsGranted("ROLE_USER")
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
    * @Route("/collection/{id}/edit", name="collection_edit")
    * 
    * @Security("is_granted('ROLE_USER') and user === element.getUser()")
    * 
    */
   public function formEdit(Collection $element = null, Request $request, EntityManagerInterface $manager){



  

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
*@IsGranted("ROLE_USER")
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
*@IsGranted("ROLE_USER")
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
*@IsGranted("ROLE_USER")
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
*@IsGranted("ROLE_USER")
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
*@IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
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
     * *@IsGranted("ROLE_USER")
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
* @Route("/collection/delete/{id}", name="collection_delete")
*
* @Security("is_granted('ROLE_USER') and user === element.getUser()")
*/
    public function delete($id, Collection $element)
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
*@IsGranted("ROLE_USER")
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
*@IsGranted("ROLE_USER")
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
     * 
     * @IsGranted("ROLE_USER")
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
     * *@IsGranted("ROLE_USER")
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
          'site' => "Cogazer"
          
        ]);
    }



    /**
     * @Route("/account/follow", name="follow")
     * *@IsGranted("ROLE_USER")
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



                                            //  GESTION DE LA WISHLIST !!!!!




       

    /**
     * @Route("/wishlist/new", name="wishlist_create")
     * *@IsGranted("ROLE_USER")
     */
    public function formWish(Wishlist $element = null, Request $request, EntityManagerInterface $manager){



        if(!$element){

            $element = new Wishlist();

        }

        // Je veux récupérer l'id de l'user (TEST)
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        

            $form = $this->createForm(WishlistType::class, $element);
                            

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $element -> setUser($user);
                $manager->persist($element);
                $manager->flush();

                $this->addFlash('wishadd', 'Cet élément a bien été ajouté à votre wishlist');

                return $this->redirectToRoute('wish_show', ['id'=> $element->getId()]);
            }


        return $this->render('site/wishlistcreate.html.twig',[
            'formCollection' => $form->createView(),
            'editMode' => $element->getId() !== null
            
            
        ]);

    }

 /**
 * @Route("/wishlist/{id}/edit", name="wishlist_edit")
 * 
 * @Security("is_granted('ROLE_USER') and user === element.getUser()")
 */
   
    
   public function formWishEdit(Wishlist $element = null, Request $request, EntityManagerInterface $manager){



       if(!$element){

           $element = new Wishlist();

       }

       // Je veux récupérer l'id de l'user (TEST)
       // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       $user = $this->getUser();
       
       

           $form = $this->createForm(WishlistType::class, $element);
                           

           $form->handleRequest($request);

           if($form->isSubmitted() && $form->isValid()){
               $element -> setUser($user);
               $manager->persist($element);
               $manager->flush();

               $this->addFlash('wishadd', 'Cet élément a bien été ajouté à votre wishlist');

               return $this->redirectToRoute('wish_show', ['id'=> $element->getId()]);
           }


       return $this->render('site/wishlistcreate.html.twig',[
           'formCollection' => $form->createView(),
           'editMode' => $element->getId() !== null
           
           
       ]);

   }


    /**
     * @Route("/wishlist/{id}", name="wish_show")
     * @IsGranted("ROLE_USER")
     */
    public function Wishshow($id)
    {
        $repo = $this->getDoctrine()->getRepository(Wishlist::class);
        $element = $repo->find($id);

        return $this->render('site/wishshow.html.twig' ,[
            'collection'=> $element
        ]);
    }


    /**
     * @Route("/wishlist", name="wishlist")
     * *@IsGranted("ROLE_USER")
     */
    public function WishIndex(EntityManagerInterface $manager,  Request $request)
    {

    
        // Je récupère l'ID de l'user connecté pour n'afficher que ses propres éléments de collection
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser()->getId();

        $q = $request->query->get('q');

        // Je crée la requête DQL qui récupère dans la table collection les éléments en rapport avec l'id de l'utilisateur connecté
        $listes = $manager->createQuery("SELECT w FROM App\Entity\Wishlist w WHERE w.user = $user AND w.title LIKE '%$q%' ORDER BY w.id DESC")->getResult(); 

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

        return $this->render('site/wishlist.html.twig', [
            'listes' => $listes,
            'pagelistes'=> $pagelistes,
            'cat' => $cat,
          
        ]);
    }


                        // SUPPRESSION ELEMENT WISHLIST

/**
*@Route("/wishlist/delete/{id}", name="wishlist_delete")
* @Security("is_granted('ROLE_USER') and user === element.getUser()")
*/
public function wishDelete($id, Wishlist $element)
{
    $repo = $this->getDoctrine()->getManager();
    $element = $repo->getRepository(Wishlist::class)->find($id);



    if( isset($_POST['confirm'])){

        $repo->remove($element);
        $repo->flush();

        $this->addFlash('wishdelete', 'Cet élément a bien été supprimé de votre wishlist');

        return $this->redirectToRoute('wishlist');

       
    }



    return $this->render('site/wishlistdelete.html.twig' ,[
        'collection'=> $element
    ]);
}



                                        // WISHLIST DES AUTRES USERS
/**
*@Route("/theirwishes/{id}", name="their_wishes")
*@IsGranted("ROLE_USER")
*/
public function otherWishlist(EntityManagerInterface $manager, Request $request, $id)
{

    $s = $request->query->get('u');

       
    $userCollection = $manager->createQuery("SELECT w FROM App\Entity\Wishlist w WHERE  w.user = $id AND w.title LIKE '%$s%' ORDER BY w.id DESC")->getResult(); 


    $pagelistes = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
        $userCollection,
        // Definir le parametre de la page
        $request->query->getInt('page', 1),
            // Nombre d'objets par page
        18
    );

    return $this->render('site/theirwishes.html.twig' ,[
       'listes'=> $userCollection,
       'pagelistes' => $pagelistes,

    ]);
}

}
