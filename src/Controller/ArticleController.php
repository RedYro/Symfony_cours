<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isNan;

class ArticleController extends AbstractController
{
    // "findById" method 

    #[Route('/article/{id}', name: 'app_article', requirements: ['id' => "[0-9]+"])]
    public function index(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository, int $id): Response
    {
        $articleId = $articlesRepository->findOneById($id);
        $categories = $categoriesRepository->findAll();

        // if(!is_numeric($id)){
        //     return $this->redirectToRoute('app_home');
        // }

        if(!$articleId){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('article/index.html.twig', [
            'articleInfo' => $articleId,
            'categories' => $categories
        ]);
    }

    //------------------------------------------------------------------------------------------------

    #[Route('/category/{name}', name: 'app_home_category')]
    public function selectCategory(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        return $this->render('article/index.html.twig', [
            'categories' => $categories
        ]);
    }

    //------------------------------------------------------------------------------------------------

    // "findByTitle" method 

    // #[Route('/article/{title}', name: 'app_article')]
    // public function index(ArticlesRepository $articlesRepository, string $title): Response
    // {
    //     $articleTitle = $articlesRepository->findByTitle($title);
    //     return $this->render('article/index.html.twig', [
    //         'article' => $articleTitle,
    //     ]);
    // }
}
