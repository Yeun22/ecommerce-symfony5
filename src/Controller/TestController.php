<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $tva = $this->calculator->calcul(200);
        dump($tva);
        dd('ça fonctionne ?');
    }
    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET","POST"}, host="localhost", schemes={"http","https"})
     */
    public function test(Request $request, $age)
    {
        // $request = Request::createFromGlobals();
        // $age = $request->attributes->get('age', 0);

        return new Response("vous avez $age ans ");
    }
}
