<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SebType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SebController extends AbstractController
{
    #[Route('/seb', name: 'app_seb')]
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SebType::class, $sortie);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('seb/seb.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView()
        ]);
    }
}
