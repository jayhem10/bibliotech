<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;




use App\Entity\Collection;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\CollectionRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Form\ElementType;

class SiteController extends AbstractController
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

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();          

        return $this->render('site/index.html.twig', [
          'listes'=> $listes,
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
        

    

        return $this->render('site/cd.html.twig' ,[
            'cds'=> $cds
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
        

    

        return $this->render('site/jeux.html.twig' ,[
            'jeux'=> $jeux
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
        

    

        return $this->render('site/livre.html.twig' ,[
            'livres'=> $livres
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
        

    

        return $this->render('site/bluray.html.twig' ,[
            'blurays'=> $blurays
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
        

    

        return $this->render('site/dvd.html.twig' ,[
            'dvds'=> $dvds
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
        

    

        return $this->render('site/vinyle.html.twig' ,[
            'vinyles'=> $vinyles
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



    return $this->render('site/otheruser.html.twig' ,[
        'users'=> $users
    ]);
}



    /**
     * @Route("/others/{id}", name="this_user")
     */
    public function userCollection(EntityManagerInterface $manager,  Request $request, $id)
    {

        $u = $request->query->get('u');
        

        // Je crée la requête DQL qui récupère dans la table collection les éléments en rapport avec l'id de l'utilisateur connecté
        $userCollection = $manager->createQuery("SELECT c FROM App\Entity\Collection c WHERE  c.user = $id AND c.title LIKE '%$u%' ORDER BY c.id DESC")->getResult(); 

        // Je récupère la catégorie des éléments
        $cat = $manager->createQuery("SELECT k FROM App\Entity\Category k")->getResult();          

        return $this->render('site/thisuser.html.twig', [
          'listes'=> $userCollection,
          'cat' => $cat,
          
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


}
