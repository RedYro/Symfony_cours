<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article')]
    public function index(ArticlesRepository $articlesRepository, int $id): Response
    {
        $articleId = $articlesRepository->findById($id);
        // $articleInfo = $articlesRepository->findAll();
        return $this->render('article/index.html.twig', [
            'article' => $articleId,
            // 'articleInfo' => $articleInfo
        ]);
    }
}
