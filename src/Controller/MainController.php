<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ListeSortiesFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class MainController extends AbstractController
{
    /** * @Route("/sorties", name="main_list") */
    public function connexion(): Response
    {
        $filtreListe= $this->createForm(ListeSortiesFormType::class);

        return $this->render('main/list.html.twig',['filtreListe'=>$filtreListe->createView()]);

    }

    /**
     * @Route("/profil", name="main_profil")
     */
    public function profil(EntityManagerInterface $entityManager): Response
    {
        $this->getRepository(Participant::class)->findAll();

        return $this->render('main/profil.html.twig');
    }


}