<?php

namespace App\Controller;

use Twig\Environment;
use App\Taxes\Detector;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController {

    protected $calculator;

    /**
     * dependency injection example
     */
    public function __construct(LoggerInterface $logger, Calculator $calculate, Slugify $slugify) {
        $this->logger = $logger;
        $slugify->slugify('test');
        $this->calculator = $calculate;
    }

    /**
     * @Route("/hello/{name}", name="hello", methods={"GET", "POST"}, host="localhost", schemes={"http", "https"})
     */
    public function hello(?string $name = 'hh') {
        $html = $this->render('hello.html.twig', ['name' => $name, 'age' => 20]);

        return new Response($html);
    }
}