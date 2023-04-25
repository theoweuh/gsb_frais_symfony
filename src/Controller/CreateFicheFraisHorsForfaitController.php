<?php

namespace App\Controller;

use App\Entity\FicheFrais;
use App\Entity\LigneFraisHorsForfait;
use App\Form\CreateLigneFraisHorsForfaitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ligne_frais_hors_forfait')]
class CreateFicheFraisHorsForfaitController extends AbstractController
{
    #[Route('/new/{id}', name: 'ligne_frais_hors_forfait_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FicheFrais $ficheFrais, EntityManagerInterface $entityManager): Response
    {
        $ligneFraisHorsForfait = new LigneFraisHorsForfait();
        $ligneFraisHorsForfait->setFicheFrais($ficheFrais);
        $form = $this->createForm(CreateLigneFraisHorsForfaitType::class, $ligneFraisHorsForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneFraisHorsForfait);
            $entityManager->flush();

            return $this->redirectToRoute('fiche_frais_show', ['id' => $ficheFrais->getId()]);
        }

        return $this->render('ligne_frais_hors_forfait/new.html.twig', [
            'ligne_frais_hors_forfait' => $ligneFraisHorsForfait,
            'form' => $form->createView(),
        ]);
    }
}
