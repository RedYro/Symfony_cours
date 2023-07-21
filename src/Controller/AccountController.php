<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(CategoriesRepository $categoriesRepository, UsersRepository $usersRepository): Response
    {
        $categories = $categoriesRepository->findAll();
        $users = $usersRepository->findAll();
        return $this->render('account/index.html.twig', [
            'categories' => $categories,
            'users' => $users,
        ]);
    }
}
