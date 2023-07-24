<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account/articles/crud')]
class ArticlesCrudController extends AbstractController
{
    #[Route('/', name: 'app_articles_crud_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('articles_crud/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articles_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles_crud/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_articles_crud_show', methods: ['GET'])]
    public function show(Articles $article, CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('articles_crud/show.html.twig', [
            'article' => $article,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles_crud/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_articles_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
