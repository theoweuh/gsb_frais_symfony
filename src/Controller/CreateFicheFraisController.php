<?php

namespace App\Controller;
use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Form\CreateLigneFraisForfaitType;
use App\Repository\FicheFraisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('newFiche')]
class CreateFicheFraisController extends AbstractController
{
    #[Route('/', name: 'app_create_fiche_frais_new')]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $date = date("Ym");
        $user = $this->getUser();
        $currentFicheFrais = $doctrine->getRepository(FicheFrais::class)->findOneBy(['user' => $user, 'mois' => $date]);

        if ($currentFicheFrais === null){
            $currentFicheFrais = new FicheFrais();
            $currentFicheFrais ->setUser($user);
            $currentFicheFrais ->setMois($date);
            $currentFicheFrais ->setNbJustificatifs(0);
            $currentFicheFrais ->setMontantValid(0);
            $currentFicheFrais ->setDateModif(new \DateTime());
            $currentFicheFrais ->setEtat($doctrine->getRepository(Etat::class)->find(2));
            $forfaitEtape = $doctrine->getManager()->getRepository(FraisForfait::class)->find(1);
            $nuite = $doctrine->getManager()->getRepository(FraisForfait::class)->find(3);
            $repas = $doctrine->getManager()->getRepository(FraisForfait::class)->find(4);
            $forfaitKm = $doctrine->getManager()->getRepository(FraisForfait::class)->find(2);

            $currentFicheFrais->addLigneFraisForfait(new LigneFraisForfait(0, $currentFicheFrais, $forfaitEtape));
            $currentFicheFrais->addLigneFraisForfait(new LigneFraisForfait(0, $currentFicheFrais, $nuite));
            $currentFicheFrais->addLigneFraisForfait(new LigneFraisForfait(0, $currentFicheFrais, $repas));
            $currentFicheFrais->addLigneFraisForfait(new LigneFraisForfait(0, $currentFicheFrais, $forfaitKm));


            $doctrine->getManager()->persist($currentFicheFrais);
            $doctrine->getManager()->flush();

        }

        $formLignesFraisForfait = $this->createForm(CreateLigneFraisForfaitType::class);
        $formLignesFraisForfait->handleRequest($request);

        if($formLignesFraisForfait->isSubmitted() && $formLignesFraisForfait->isValid()){
            $ligneFraisForfait = $currentFicheFrais->getLigneFraisForfait();
            //dd($currentFicheFrais);

            $currentFicheFrais->getLigneFraisForfait()[0]->setQuantite($formLignesFraisForfait->get('forfaitEtape')->getData());
            $currentFicheFrais->getLigneFraisForfait()[1]->setQuantite($formLignesFraisForfait->get('nuite')->getData());
            $currentFicheFrais->getLigneFraisForfait()[2]->setQuantite($formLignesFraisForfait->get('repas')->getData());
            $currentFicheFrais->getLigneFraisForfait()[3]->setQuantite($formLignesFraisForfait->get('forfaitKm')->getData());

            $doctrine->getManager()->persist($currentFicheFrais);
            $doctrine->getManager()->flush();
        }

        return $this->render('create_fiche_frais/index.html.twig', [
            'formLigneFraisForfait' => $formLignesFraisForfait,
            'formnuite' => $formLignesFraisForfait,
            'formrepas' => $formLignesFraisForfait,
            'forfaitKm' => $formLignesFraisForfait,
        ]);
    }

}

