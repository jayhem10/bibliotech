<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Collection;
use App\Entity\Category;
use App\Entity\User;

class CollectionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création de trois catégories fakes
        for($i = 1; $i <=6; $i++){
            $category =new Category();
            $category->setTitle($faker->words($nb = 1, $asText = true))
                      ->setDescription($faker->paragraph());

            $manager->persist($category);
        
        

        }


// Je donne des utilisateurs pour chaque collection
        for($k = 1; $k <=10; $k++){
            $user = new User();
            $user ->setPseudo($faker->name())
                    ->setPassword("admin")
                    ->setEmail($faker->email());

           $manager->persist($user);

// Je crée des éléments de collection pour chaque user et avec des catégories différentes
           for($j = 1 ; $j <= mt_rand(4,6); $j++)
           {
               $collection = new Collection();
               $collection ->setTitle($faker->sentence())
                           ->setAuthor($faker->name())
                           ->setEdition($faker->sentence())
                           ->setReference($faker->sentence())
                           ->setYear($faker->year($max = 'now'))
                           ->setImage($faker->imageUrl())
                           ->setNote($faker->randomDigit())
                           ->setCategory($category)
                           ->setUser($user);
                           
   
               $manager ->persist($collection);
        
           }
           
    }
    
        $manager->flush();
}
}



    //    Première façon de faire des fixtures
    
        // for($i = 1 ; $i <= 10; $i++)
        // {
        //     $collection = new Collection();
        //     $collection ->setTitle("Titre de l'/élément n°$i")
        //                 ->setAuthor("<p>Auteur:$i</p>")
        //                 ->setEdition("<p>Edition:$i</p>")
        //                 ->setReference("<p>Reference:$i</p>")
        //                 ->setType("<p>Type:$i</p>")
        //                 ->setYear("2020")
        //                 ->setImage("http://placehold.it/350x150")
        //                 ->setNote("<p>4/5</p>");
                        

        //     $manager ->persist($collection);
        // }
