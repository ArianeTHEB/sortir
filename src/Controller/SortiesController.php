<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreationSortieFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/creer', name: 'creer_sorties')]
    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response

    {
        //création d'une instance de serie
        $sortie = new Sortie();
       // $sortie->setDateCreated(new \DateTime());//attribut nécessaire pour envoi bdd mais retiré du form
        $sortieForm = $this -> createForm(CreationSortieFormType::class, $sortie);

        dump($sortie);//permet de verifier si un objet est hydraté
        $sortieForm -> handleRequest($request);
        dump($sortie);//on voit a present que mon objet serie à des arguments grace à handleRequest

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            //on crée un message flash pour signaler à l'utilisateur
            $this->addFlash('success', 'Serie added! Good job.');

            // on va à présent rediriger pour cela on utilise return
            return $this->redirectToRoute('main_list');
        }

        //passage à twig pour déclencher l'affichage du formulaire
        return $this->render('sorties/creerSortie.html.twig', [
            'sortieForm' => $sortieForm ->createView()
        ]);
    }
}
