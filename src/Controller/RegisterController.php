<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        return $this->render('register/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
