<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    // "findById" method 

    #[Route('/article/{id}', name: 'app_article')]
    public function index(ArticlesRepository $articlesRepository, int $id): Response
    {
        $articleId = $articlesRepository->findOneById($id);
        if(!$articleId){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('article/index.html.twig', [
            'articleInfo' => $articleId,
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
