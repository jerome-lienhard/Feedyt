<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Functions\Flux;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceController extends AbstractController
{

    #[Route('/espace', name: 'espace')]
    public function index(Request $rq, EntityManagerInterface $entityManager): Response
    {
        $flux = NULL;
        $request = NULL;
        // Si il y a une recherche de flux
        if ($rq->query->get("url")) {
            $request = $rq->query->get("url");
            $flux = NULL;
            if ($request) {
                $flux = new Flux;
                $flux = $flux->RSS_Display($request, 15);
            }

            if ($flux != false) {
                return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request]);
            }
            // Sinon si il y a une demande d'ajout à la liste des suivi de flux
        } elseif ($rq->query->get("ajout_url")) {

            $request = $rq->query->get("ajout_url");
            $site = new Site();
            $site->setSourceUrl($request);

            $form = $this->createForm(SiteType::class, $site);
            $form->handleRequest($rq);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($site);
                $entityManager->flush();

                return $this->redirectToRoute('espace', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "form" => $form->createView()]);
        }
        // sinon
        return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request]);
    }

    // Effacer la liste de flux

    #[Route('/espace/effacer-recherche', name: 'espace_effacer_recherche')]
    public function effacer_recherche(): Response
    {

        return $this->redirectToRoute("espace");
    }
}
