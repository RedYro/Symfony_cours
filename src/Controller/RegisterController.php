<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterType;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(CategoriesRepository $categoriesRepository, Request $request, UserPasswordHasherInterface $userPasswordHash, EntityManagerInterface $entityManagerInterface, UsersRepository $usersRepository): Response
    {   
        // Création d'un objet instance de la classe "Users"
        $user = new Users;
        // Création d'un formulaire à l'aide de la méthode "createForm()" le classe "AbstractController" 
        $form = $this->createForm(RegisterType::class, $user); // 2 paramètres pour la méthode "createForm()" // 1 - classe du formulaire // 2 - objet géré par le formulaire
        $verif = false; 
        // Le formulaire doit écouter et analyser la requête qui vient de la "vue" et vérifier si il y a un "POST" envoyé ou non
        // Utilisation de l'objet "request" crée par Symfony et qui représente la requête "HTTP" entrante (ici la requête contient les données du formulaire)
        $form->handleRequest($request); // Méthode "handleRequest" => utilisée pour traiter les données soumises par l'utilisateur (ÉTAPE IMPORTANTE)
        if($form->isSubmitted() && $form->isValid()){
            $password = $form->get("password")->getData();
            $user->setPassword($userPasswordHash->hashPassword($user, $password)); // "Hash" du "password"
            $usersDB = $usersRepository->findAll();
            foreach($usersDB as $userDB){
                if($userDB->getEmail() == $form->get("email")->getData()){
                    $verif = true;
                }
            }
            if($verif == true){
                return $this->redirectToRoute('app_register');
            } else{
                // Pour stocker les données de l'utilisateur dans la DB, on utilise "Doctrine" plus précisément l'objet instancié de la classe "EntityManagerInterface"
                // Utilisation de la méthode propre à "Doctrine" "persist()" 
                $entityManagerInterface->persist($user); // "persist()" : fige la data pour la création d'un objet (pas de besoin de cela pour la mise à jour)
                $entityManagerInterface->flush(); // Exécution et enregistement dans la DB
            }
        }

        // "$categories" pour la navbar
        $categories = $categoriesRepository->findAll();
        return $this->render('register/index.html.twig', [
            'categories' => $categories,
            'formInscription' => $form->createView(), // Passage du formulaire en variable 'template' et création de la 'vue' de ce formulaire
        ]);
    }
}
