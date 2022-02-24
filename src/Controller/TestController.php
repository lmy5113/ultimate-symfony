<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController {
    /**
     * @Route("/test/{age}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"http", "https"})
     */
    public function index(Request $request) {
        //http foundation
        $age = $request->attributes->get('age');    

        return new Response($age);
    }

}