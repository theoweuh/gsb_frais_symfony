<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\FicheFrais;

class MontantValidController extends AbstractController
{
    //#[Route('/montant/valid', name: 'app_montant_valid')]
    public function index(): Response
    {
        $montant = $this->getDoctrine()->getRepository(ListeUserMedicController::class)->findAll([],['montant']);
        return $this->render('montant_valid/index.html.twig', [
            'controller_name' => 'MontantValidController',
        ]);
    }
}
