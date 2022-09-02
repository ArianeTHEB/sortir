<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\CreationSortieFormType;
use App\Form\LieuType;
use App\Repository\EtatRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/creer', name: 'creer_sorties')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager, VilleRepository $villeRepository, EtatRepository $etatRepository): Response

    {
        //création d'une instance de sortie
        $user=$this->getUser();
        $etat = $etatRepository->findById(['2']);//super method hétitée qui permet de faire une recherche sur la colonne qu'on souhaite

        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new \DateTime('now'));
        $sortie->setDateLimiteInscription(new \DateTime('now'));
        $sortie->setEtat($etat[0]);

        $sortie->setOrganisateur($user);
        $sortie->addParticipant($user);

        $sortieForm = $this->createForm(CreationSortieFormType::class, $sortie);

        $villes = $villeRepository->findAll();

        $lieuForm = $this->createForm(LieuType::class);


     //   dump($sortie);//permet de verifier si un objet est hydraté
        $sortieForm->handleRequest($request);
    //    dump($sortie);//on voit a present que mon objet sortie à des arguments grace à handleRequest

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();

            //on crée un message flash pour signaler à l'utilisateur
            $this->addFlash('success', 'Sortie créée, bon amusement.');

            // on va à présent rediriger pour cela on utilise return
            return $this->redirectToRoute('main_list');
        }

        //passage à twig pour déclencher l'affichage du formulaire
        return $this->render('sorties/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),

        ]);
    }

}
