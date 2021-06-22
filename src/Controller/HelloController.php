<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{

    /**
     * @Route("/hello/{prenom?World}", name="hello", methods={"GET","POST"}, host="localhost", schemes={"http","https"})
     */
    public function hello($prenom)
    {
        return $this->render('hello.html.twig', ['prenom' => $prenom]);
    }
}
