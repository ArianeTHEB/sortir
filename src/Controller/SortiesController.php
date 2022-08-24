<?php

namespace App\Controller;

use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/sorties', name: 'sorties_list')]
    public function list(CampusRepository $campusRepository): Response
    {
        $var = "var";
        return $this->render('sorties/list.html.twig',['var'=>$var]);
    }
}
