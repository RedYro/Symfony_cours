<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    // À ce niveau et avec cette action, il faut récupérer les articles et les stocker dans une variable
    // Afin de récupérer les données des articles, on utilise le "repository" lié à l'entité en question
    // Cela s'appelle le mécaniqme d'injection de dépendance en Symfony => il s'agit de dire à Symfony que l'on veut rentrer dans cette action en embarquant avec l'objet "ArticlesRepository" qui est une instance stocké dans "$articlesRepository"
    public function index(ArticlesRepository $articlesRepository): Response
    {
        // On se sert de la variable "$articlesRepository" pour utiliser les méthodes et interroger la DB
        $articles = $articlesRepository->findAll(); // Array contenant tous les articles
        // dd($articles); // "dd()" (don't die) => fonction similaire à "debug()"
        return $this->render('home/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
