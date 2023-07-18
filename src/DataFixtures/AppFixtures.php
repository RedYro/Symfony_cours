<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\Categories;
use EsperoSoft\Faker\Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = new Faker(); // Instanciation de la classe "Faker" pour créer un objet nous permettant de générer des données
        // Création de données concernant les catégories avec une boucle "for"
        $categories = []; // "Array" vide pour stocker les catégories 
        for ($i = 0; $i < 5; $i++){
            $category = (new Categories())->setName($faker->word())
                                            ->setDescription($faker->text())
                                            ->setCreatedAt($faker->dateTimeImmutable());
            $categories[] = $category; // À chaque tour de boucle, stockage d'une catégorie dans le tableau
            $manager->persist($category); // À chaque tour de boucle, on fige les données pour ensuite les insérer dans la DB avec la méthode "flush"
        }
        // Création de données concernant les articles avec une boucle "for"
        for ($i = 0; $i < 50; $i++){
            $article = (new Articles())->setTitle($faker->word(4))
                                        ->setContent($faker->text())
                                        ->setImage($faker->image())
                                        ->setCreatedAt($faker->dateTimeImmutable())
                                        ->addCategory($categories[rand(0,count($categories)-1)]); // count($categories)-1) = 4
                                        // Récupération de la catégorie du tableau "$categories", génération d'un nombre aléatoire entre 0 et 4 (count($categories)-1) = 4)
            $manager->persist($article);
        }

        $manager->flush(); // Étape permettant d'insérer dans la DB
    }
}
