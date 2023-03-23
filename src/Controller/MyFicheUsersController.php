<?php
namespace App\Controller;
use App\Entity\FicheFrais;
use App\Repository\FicheFraisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;



#[Route('/listemois')]
class MyFicheUsersController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $registery = $doctrine->getRepository(FicheFrais::class);
        $lesFicheFraisDuUser = $registery->findBy(['user' => $user]);

        foreach ($lesFicheFraisDuUser as $uneFicheFrais){
            $listMois[] = $uneFicheFrais->getMois();
        }
        dd($listMois);


        return $this->render('list_users/index.html.twig', [
            'fichefraisUser' => $lesFicheFraisDuUser,


        ]);


    }


}

