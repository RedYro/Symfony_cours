<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    // À ce niveau et avec cette action, il faut récupérer les articles et les stocker dans une variable
    // Afin de récupérer les données des articles, on utilise le "repository" lié à l'entité en question
    // Cela s'appelle le mécaniqme d'injection de dépendance en Symfony => il s'agit de dire à Symfony que l'on veut rentrer dans cette action en embarquant avec l'objet "ArticlesRepository" qui est une instance stocké dans "$articlesRepository"
    public function index(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        // On se sert de la variable "$articlesRepository" pour utiliser les méthodes et interroger la DB
        $articles = $articlesRepository->findAll(); // Array contenant tous les articles
        // dd($articles); // "dd()" (don't die) => fonction similaire à "debug()"
        // Récupération des catégories 
        $categories = $categoriesRepository->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    //----------------------------------------------------------------

    #[Route('/category/{name}', name: 'app_home_category')]
    public function selectCategory(CategoriesRepository $categoriesRepository, ArticlesRepository $articlesRepository, string $name): Response
    {
        $categories = $categoriesRepository->findAll();
        $articles = $articlesRepository->findAll();
        $category = $categoriesRepository->findOneByName($name);

        // if(!$category){
        //     return $this->redirectToRoute('app_home');
        // }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles' => $articles,
            'category' => $category // Variable pour afficher les articles dans chaque catégorie
        ]);
    }
}
