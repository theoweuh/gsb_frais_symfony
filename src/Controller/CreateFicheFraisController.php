<?php

namespace App\Controller;
use App\Repository\FicheFraisRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('listemois/newFiche')]
class CreateFicheFraisController extends AbstractController
{
    #[Route('listemois/newFiche', name: 'app_create_fiche_frais_new')]
    public function index(Request $request, FicheFraisRepository $ficheFraisRepository): Response
    {
        return $this->render('create_fiche_frais/index.html.twig', [
            'controller_name' =>$ficheFraisRepository->findOneBy(),
        ]);
    }

}
