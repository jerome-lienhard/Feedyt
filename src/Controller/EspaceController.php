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

        // Recupération de tous les site et nommage
        if ($siteRepository->findAll()) {
            $siteurl = $siteRepository->findAll();
            foreach ($siteurl as $sit) {
                if ($sit->getName() == null) {
                    $sit->setName(substr(substr(strstr($sit->getSourceUrl(), '.'), 1), 0, -1));
                }
            }
        }
        //Préparation pour l'affichage des follows de l'utilisateur connecté pour la navbar
        foreach ($actu as $flu) {
            $affiche = $flu->getSite()->getSourceUrl();
            if ($flu->getName() == null) {
                $flu->setName($flu->getSite()->getName());
            }
            $Tabnews[] = "<h5 class='alert alert-secondary mt-5'> Publié par : " . $flu->getSite()->getName() . "</h5>" . $news->RSS_Display($affiche, 3);
        }

        // S'il y a une recherche de flux
        if ($rq->query->get("url")) {
            $request = $rq->query->get("url");
            if ($request) {
                $flux = new Flux;
                $flux = $flux->RSS_Display($request, 10);
            }

            if ($flux != false) {
                return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "news" => $Tabnews, "site" => $siteurl, "follow" => $actu]);
            }
            // Sinon s'il y a une demande d'ajout à la liste des suivi de flux
        } elseif ($rq->query->get("ajout_url")) {

            $request = $rq->query->get("ajout_url");
            foreach ($actu as $fol) {
                if ($fol->getSite()->getSourceUrl() == $request) {
                    $this->addFlash("warning", "Ce site <b>existe</b> déjà dans ta liste de flux, il n'y sera pas ajouté.");
                    return $this->redirectToRoute('espace', [], Response::HTTP_SEE_OTHER);
                }
            }
            $site = new Site();
            $site->setSourceUrl($request);
            $date = new DateTime();
            $site->setCreatesAt($date);
            $flux = new Flux;
            $flux = $flux->RSS_Display($request, 10);

            // Création du formulaire d'ajout à la liste de follow
            $form = $this->createForm(SiteType::class, $site);
            $form->handleRequest($rq);
            // Si le formulaire est validé
            if ($form->isSubmitted() && $form->isValid()) {

                // Si ce site existe déjà en bdd, ajout dans la liste des follows de l'utilisateur
                if ($siteRepository->findByUrl($request)) {
                    $siteid = $siteRepository->findByUrl($request);
                    $follow = new Follow();
                    $user = $this->getUser();
                    $follow->setUser($user);
                    $follow->setSite($siteid[0]);
                    $follow->setName($siteid[0]->getName());
                    $entityManager->persist($follow);
                    $entityManager->flush();

                    // Sinon ajout du site à la bdd + ajout du site dans la liste des follows de l'utilisateur
                } else {
                    // Ajout du site en bdd
                    $site->setName(substr(substr(strstr($request, '.'), 1), 0, -1));
                    $entityManager->persist($site);
                    $entityManager->flush();
                    // Ajout du site dans la liste des follows de l'utilisateur
                    $siteid = $siteRepository->findByUrl($request);
                    $follow = new Follow();
                    $user = $this->getUser();
                    $follow->setUser($user);
                    $follow->setSite($siteid[0]);
                    $follow->setName($siteid[0]->getName());
                    $entityManager->persist($follow);
                    $entityManager->flush();
                }


                return $this->redirectToRoute('espace', ["site" => $siteid], Response::HTTP_SEE_OTHER);
            }

            return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "form" => $form->createView(), "news" => $Tabnews, "follow" => $actu]);
        }
        $flux = NULL;
        $request = NULL;
        // sinon
        return $this->render('espace/index.html.twig', ["flux" => $flux, "url_ajout" => $request, "news" => $Tabnews, "follow" => $actu]);
    }

    // Effacer la liste de flux
    #[Route('/espace/effacer-recherche', name: 'espace_effacer_recherche')]
    public function effacer_recherche(): Response
    {

        return $this->redirectToRoute("espace");
    }

    //Changer la route mettre dans follow ou bien faire la récupération d'abjet follow.
    #[Route('/espace/follow/{id}', name: 'espace_follow')]
    public function lire_un_follow(Follow $follow, SiteRepository $siteRepository): Response
    {
        $chemin = "../.";
        $user = $this->getUser();
        $actu = $user->getFollows();

        // Recupération de tous les site et nommage
        if ($siteRepository->findAll()) {
            $siteurl = $siteRepository->findAll();
            foreach ($siteurl as $sit) {
                if ($sit->getName() == null) {
                    $sit->setName(substr(substr(strstr($sit->getSourceUrl(), '.'), 1), 0, -1));
                }
            }
        }
        // Récupération des flux
        foreach ($actu as $flu) {

            if ($flu->getName() == null) {
                $flu->setName($flu->getSite()->getName());
            }
        }

        $flux = null;

        if ($follow->getSite()) {
            $site = $follow->getSite();
            if ($site->getName() == null) {
                $site->setName(substr(substr(strstr($site->getSourceUrl(), '.'), 1), 0, -1));
            }
            $flux = new Flux;
            $flux = $flux->RSS_Display($site->getSourceUrl(), 10);
        }


        return $this->render('espace/follow.html.twig', ["chemin" => $chemin, "flux" => $flux, "site" => $follow, "follow" => $actu]);
    }
}
