<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
// chope les request sans besoin des global PHP reloues
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Twig\Environment;

// route = autre facon plus simple de faire les routes yaml, S\C\R\A\R lis les anotation pour les routes

class HelloController 

{

    public function __Construct(Slugify $slugify, Environment $twig)
    {
        $this->twig = $twig;
        dump($slugify->slugify('Nicolas est une merde'));
    }

    /**
     * @Route("/hello/{prenom?World}", name="hello")
     */

    public function sayHello(Request $request): Response
    {

        $prenom = $request->attributes->get("prenom");
        //les parametres de la requete se trouve dans l'url, POST
       
        // dd($request->query->get("prenom"));
        //apres le ? c'est  dans la query, GET

        $html = $this->twig->render("hello.html.twig", [
            'prenom' => $prenom
        ]);

        return new Response($html);
        // envoie la reponse au kernel
    }
}