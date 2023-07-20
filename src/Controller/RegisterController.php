<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterType;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(CategoriesRepository $categoriesRepository, RegisterType $registerType): Response
    {   
        // Création d'un objet instance de la classe "Users"
        $user = new Users;
        // Création d'un formulaire à l'aide de la méthode "createForm()" le classe "AbstractController" 
        $form = $this->createForm(RegisterType::class, $user); // 2 paramètres pour la méthode "createForm()" // 1 - classe du formulaire // 2 - objet géré par le formulaire

        // "$categories" pour la navbar
        $categories = $categoriesRepository->findAll();
        return $this->render('register/index.html.twig', [
            'categories' => $categories,
            'formInscription' => $form->createView() // Passage du formulaire en variable 'template' et création de la 'vue' de ce formulaire
        ]);
    }
}
