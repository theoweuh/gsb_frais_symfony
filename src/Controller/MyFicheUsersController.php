<?php
namespace App\Controller;
use App\Entity\FicheFrais;
use App\Repository\FicheFraisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class MyFicheUsersController extends AbstractController
{

    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $registery = $doctrine->getRepository(FicheFrais::class);
        $fichefraisUser = $registery->findBy(['user' => $user]);

        return $this->render('list_users/index.html.twig', [
            'fichefraisUser' => $fichefraisUser,

        ]);

    }

}

