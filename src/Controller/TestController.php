<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Ce "Controller" est géré par la class "TestController" qui hérite de la class "AbstractController" => classe abstraite avec plusieurs méthodes que nous pourrons utiliser après dans les classes "filles", il faut indiquer la provencance de la class "AbstractController" dans ce fichier via ("use" + le chemin des / du dossier(s))
// Dans un "Controller" il y a des actions => méthodes se trouvant dans le "controller"
class TestController extends AbstractController
{
    // '/test' => chemin de la "Route"
    // 'app_test' => nom de la "Route" qui servira au moment de l'appel des "Routes" dans Twig
    #[Route('/test', name: 'app_test')] // Cette ligne représente les annotations : permet d'indiquer que la route "test" appele l'action "index" afin d'avoir comme affichage le rendu du fichier "index.html.twig" se trouvant dans le dossier "test" dans "templates"
    // Possibilité de préciser la "Route" d'une autre façon : 'routes.yaml' 
    public function index(): Response // Chaque action répond avec une "Response"
    {   
        $id = 0;
        // Action "index()" retrourne la méthode "render" qui provient de la class "AbstractController"
        return $this->render('test/index.html.twig', [ // "render" prend 2 arguments  : 
            // 1 - Le fichier que l'on veut rendre (gérer par "ce" "Controller")
            // 2 - Les données que l'on veut fournir à cette page
            'controller_name' => 'TestController', // Ici, injecte une variable "controller_name" avec sa valeur : chaîne de caractères "TestController" 
            'day' => 'jeudi',
            'id' => $id
        ]);
    }

    //--------------------------------------------------------------------------

    // Possibilité de créer des "Routes" dynamique en passant des variables dans le chemin 
    #[Route('/test/{id}/{name}', name: 'app_test_id', requirements: ["name" => "[a-zA-Z]+"])] // Pour préciser et identifier dans la route que l'on a une variable, on met des accolades "{}" 
    public function indexId(int $id, string $name): Response // Afin d'envoyer la valeur dans "view", il faut la passer dans l'action
    {      
        // id : si dans l'URL on passe une chaîne de caractères à la place de l'entier, cela sera pris en compte et affichera "Hello $name !"
        // On peut valider les paramètres passer en typant ces derniers
        // On peut aussi utiliser l'annotation avec une information supplémentaire :
        //  - "requirements: ["name" => "[a-zA-Z]+"]"
        // "requirements" : ["le paramètre sur lequel on veut avoir plus de précision => "expression régulière"] (REGEX)
        return $this->render('test/index.html.twig', [ 
            'controller_name' => 'TestController',
            'day' => 'jeudi',
            'id' => $id,
            'name' => $name
        ]);
    }
}
