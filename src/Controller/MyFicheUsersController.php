<?php
namespace App\Controller;
use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\User;
use App\Form\CreateLigneFraisForfaitType;
use App\Form\EtatType;
use App\Form\FraisForfaitType;
use App\Form\MesFichesFraisType;
use App\Repository\FicheFraisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MyFicheUsersController extends AbstractController
{
    #[Route('/listemois', name: "liste_mois")]
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $selecetedFicheFrais = null;
        $registery = $doctrine->getRepository(FicheFrais::class);
        $lesFicheFraisDuUser = $registery->findBy(['user' => $user]);

        foreach ($lesFicheFraisDuUser as $uneFicheFrais){
            $listMois[$uneFicheFrais->getMois()] = $uneFicheFrais->getMois();
        }

        $myForm =$this->createForm(MesFichesFraisType::class, null,[
            'liste_mois'=> $listMois,
        ]);


        $myForm->handleRequest($request);
        if ($myForm->isSubmitted() && $myForm->isValid()){
           $selecetedFicheFrais = $registery->findOneBy(['user'=>$user,'mois'=>$myForm->getData()]);
        }
        return $this->render('my_fiche_users/index.html.twig', [
            'myForm' => $myForm,
            'selectedFicheFrais' => $selecetedFicheFrais,
        ]);

    }

}

