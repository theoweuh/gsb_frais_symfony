<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class ListeUserMedicController extends AbstractController
{

    //#[Route('/liste/user/medic', name: 'app_liste_user_medic')]
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(ListeUserMedicController::class)->findAll([],['user']);
        return $this->render('liste_user_medic/index.html.twig', [
            'controller_name' => 'ListeUserMedicController',
        ]);

    }




}
