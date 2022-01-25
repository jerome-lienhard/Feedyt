<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie; 


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function mentions(): Response
    {
        return $this->render('home/mentions_legales.html.twig', []);
    } 
}

    


