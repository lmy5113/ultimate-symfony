<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController {

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
     * @Route("/hello/{name?world}", name="hello", methods={"GET", "POST"}, host="localhost", schemes={"http", "https"})
     */
    public function hello(?string $name, LoggerInterface $logger, Calculator $c) {
        $this->logger->info('test logger');
        
        return new Response("Hello {$c->calculate(100)}");
    }
}