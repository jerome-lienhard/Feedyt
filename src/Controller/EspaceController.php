<?php

namespace App\Controller;

use App\Functions\Flux;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceController extends AbstractController
{

    #[Route('/espace', name: 'espace')]
    public function index(Request $rq): Response
    {

        $request = $rq->query->get("url");
        $flux = NULL;
        if ($request) {
            $flux = new Flux;
            $flux = $flux->RSS_Display($request, 15);
        }

        if ($flux != false) {
            return $this->render('espace/index.html.twig', ["flux" => $flux]);
        }
        return $this->render('espace/index.html.twig', ["flux" => $flux]);
    }

    #[Route('/espace/effacer-recherche', name: 'espace_effacer_recherche')]
    public function effacer_recherche(): Response
    {

        return $this->redirectToRoute("espace");
    }
}
