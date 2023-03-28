<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\User;
use App\Repository\FicheFraisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrimeController extends AbstractController
{
    #[Route('/prime', name: 'app_prime')]
    public function index(ManagerRegistry $registry): Response
    {
        $repository = $registry->getRepository(FicheFrais::class);
        $ficheFrais = $repository->findAll();
        $fichefrais2021 = [];
        foreach ($ficheFrais as $uneFicheFrais) {
            if (str_contains($uneFicheFrais->getMois(), '2021')){
                $fichefrais2021[] = $uneFicheFrais;

            }
        }

        $montantsValidesCumules = 0;
        $montantsLignesFraisCumules = 0;
        foreach ($fichefrais2021 as $uneFicheFrais){
            $montantsValidesCumules += $uneFicheFrais->getMontantValid();
            $montantsLignesFraisCumules += $uneFicheFrais->getMontantLignesFrais();
        }

        $allUsers = $registry->getRepository(User::class)->findAll();
        $nbVisiteurs = count($allUsers);
        $montantsValidesCumulesParVisiteur = $montantsValidesCumules / $nbVisiteurs;

        return $this->render('prime/index.html.twig', [
            'primeMontantValidesCumules' => $montantsValidesCumules * 0.095,
            'primeMontantValideCumulesParVisiteur' => $montantsValidesCumulesParVisiteur * 0.095 / $nbVisiteurs,
            'montantCumules' => $montantsLignesFraisCumules * 0.095,
            'montantCumulesParVisiteur' => $montantsLignesFraisCumules * 0.095 / $nbVisiteurs
        ]);
    }
}
