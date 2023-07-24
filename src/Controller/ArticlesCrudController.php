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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/account/articles/crud')]
class ArticlesCrudController extends AbstractController
{
    #[Route('/', name: 'app_articles_crud_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
    {
        $user = $this->getUser();
        return $this->render('articles_crud/index.html.twig', [
            'articles' => $articlesRepository->findByAuthor($user),
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articles_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository, SluggerInterface $slugger): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté
            $user = $this->getUser();
            // Stockage "user" connecté dans la propriété "author" de l'entité "Articles"
            $article->setAuthor($user);
            // Récupération "categories"
            $categoriesArticles = $article->getCategories()->getValues();
            foreach($categoriesArticles as $categoryArticle){
                $categoryArticle->addArticle($article);
                $entityManager->persist($categoryArticle); // "persist()" permt de figer les données avant de les envoyer dans la DB via "flush()"
            }
            // Récupération image
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // updates the 'imagename' property to store the PDF file name
            // instead of its contents
            $article->setImage($newFilename);

            // dd($article);
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
