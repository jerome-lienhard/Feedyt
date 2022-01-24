<?php

namespace App\Controller;

use App\Entity\Follow;
use App\Entity\Site;
use App\Form\SiteType;
use App\Functions\Flux;
use App\Repository\SiteRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceController extends AbstractController
{

    #[Route('/espace', name: 'espace')]
    public function index(Request $rq, EntityManagerInterface $entityManager, SiteRepository $siteRepository): Response
    {
        // Affichage des fulx des follows de l'utilisateur connecté
        $user = $this->getUser();
        $actu = $user->getFollows();
        $news = new Flux;
        $Tabnews = Null;
        $menu = null;
        $lien_menu = null;
        if ($siteRepository->findAll()) {
            $siteurl = $siteRepository->findAll();
        }

        foreach ($actu as $flu) {
            $affiche = $flu->getSite()->getSourceUrl();
            $menu[] = $affiche;
            $lien_menu[] = substr(substr(strstr($affiche, '.'), 1), 0, -1);;
            $Tabnews[] = $news->RSS_Display($affiche, 3);
        }

        // Si il y a une recherche de flux
        if ($rq->query->get("url")) {
            $request = $rq->query->get("url");
            if ($request) {
                $flux = new Flux;
                $flux = $flux->RSS_Display($request, 10);
            }

            if ($flux != false) {
                if ($menu == null) {
                    return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "news" => $Tabnews]);
                }
                return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "news" => $Tabnews, "menu" => $menu, "lien_menu" => $lien_menu, "site" => $siteurl]);
            }
            // Sinon s'il y a une demande d'ajout à la liste des suivi de flux
        } elseif ($rq->query->get("ajout_url")) {

            $request = $rq->query->get("ajout_url");
            $site = new Site();
            $site->setSourceUrl($request);
            $date = new DateTime();
            $site->setCreatesAt($date);
            $flux = new Flux;
            $flux = $flux->RSS_Display($request, 10);

            $form = $this->createForm(SiteType::class, $site);
            $form->handleRequest($rq);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($siteRepository->findByUrl($request)) {
                    // Si ce site existe déjà en bdd, ajout dans la liste des follows de l'utilisateur
                    $siteid = $siteRepository->findByUrl($request);
                    // $siteid = $siteid[0]->getId();
                    $follow = new Follow();
                    $user = $this->getUser();
                    $follow->setUser($user);
                    $follow->setSite($siteid[0]);
                    $entityManager->persist($follow);
                    $entityManager->flush();
                } else {
                    // Sinon ajout du site à la bdd + ajout du site dans la liste des follows de l'utilisateur
                    // Ajout du site en bdd
                    $entityManager->persist($site);
                    $entityManager->flush();
                    $siteid = $siteRepository->findByUrl($request);
                    // $siteid = $siteid[0]->getId();
                    $follow = new Follow();
                    $user = $this->getUser();
                    $follow->setUser($user);
                    $follow->setSite($siteid[0]);
                    $entityManager->persist($follow);
                    $entityManager->flush();
                }


                return $this->redirectToRoute('espace', ["site" => $siteid], Response::HTTP_SEE_OTHER);
            }

            return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "form" => $form->createView(), "news" => $Tabnews, "menu" => $menu, "lien_menu" => $lien_menu]);
        }
        $flux = NULL;
        $request = NULL;
        // sinon
        return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "news" => $Tabnews, "menu" => $menu, "lien_menu" => $lien_menu, "site" => $siteurl]);
    }

    // Effacer la liste de flux
    #[Route('/espace/effacer-recherche', name: 'espace_effacer_recherche')]
    public function effacer_recherche(): Response
    {

        return $this->redirectToRoute("espace");
    }
    #[Route('/espace/follow/{id}', name: 'espace_follow')]
    public function lire_un_follow(Site $site, SiteRepository $siteRepository): Response
    {
        $chemin = "../.";
        $user = $this->getUser();
        $actu = $user->getFollows();
        foreach ($actu as $flu) {
            $affiche = $flu->getSite()->getSourceUrl();
            $lien_menu[] =  substr(substr(strstr($affiche, '.'), 1), 0, -1);;
        }
        $flux = null;
        if ($site->getSourceUrl()) {
            $site_source = $site->getSourceUrl();
            $flux = new Flux;
            $flux = $flux->RSS_Display($site_source, 10);
        }

        if ($siteRepository->findAll()) {
            $siteurl = $siteRepository->findAll();
        }

        return $this->render('espace/follow.html.twig', ["lien_menu" => $lien_menu, "chemin" => $chemin, "flux" => $flux, "site" => $siteurl, "source" => $site_source]);
    }
}
