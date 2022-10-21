<?php

namespace App\Controller;
use App\Entity\Etat;
use App\Entity\FicheFrais;
use App\Entity\FraisForfait;
use App\Entity\LigneFraisForfait;
use App\Entity\LigneFraisHorsForfait;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use phpDocumentor\Reflection\Types\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataimportController extends AbstractController
{
    #[Route('/dataimport', name: 'app_dataimport')]
    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {


        $json = file_get_contents("visiteur.json");

        $users = json_decode($json);

        foreach ($users as $user){

            $newUser=new User();
            $newUser->setOldid($user->id);
            $newUser->setLogin($user->login);
            $newUser->setPrenom($user->prenom);
            $newUser->setNom($user->nom);
            $newUser->setCp($user->cp);
            $newUser->setVille($user->ville);
            $newUser->setDateEmbauche(new \DateTime());
            $plaintextpassword = $user->mdp;
            $newUser->setAdresse($user->adresse);
            $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword);
            $newUser->setPassword($hashedpassword);
            $doctrine->getManager()->persist($newUser);
            $doctrine->getManager()->flush();

        }

        var_dump($users);

        return $this->render('dataimport/index.html.twig', [
            'controller_name' => 'DataimportController',
        ]);
    }


    #[Route('/importfiches', name: 'app_data_importfiches')]
    public function importFicheFrais(ManagerRegistry $doctrine): Response
    {
        $fichesfraisjson = file_get_contents("./fichefrais.json");
        $fichesfrais = json_decode($fichesfraisjson);


        foreach ($fichesfrais as $fichefrais)
        {
            $newFicheFrais=new FicheFrais();
            $newFicheFrais->setMois($fichefrais->mois);
            $newFicheFrais->setMontantValid($fichefrais->montantValide);
            $newFicheFrais->setNbJustificatifs($fichefrais->nbJustificatifs);
            $newFicheFrais->setDateModif(new \DateTime($fichefrais->dateModif));
            $user=$doctrine->getRepository(User::class)->findOneBy(['oldId' => $fichefrais->idVisiteur]);
            $newFicheFrais->setUser($user);

            switch ($fichefrais->idEtat){
                case "VA":
                    $etat = $doctrine->getRepository(Etat::class)->find(4);
                    break;

                case "RB":
                    $etat = $doctrine->getRepository(Etat::class)->find(3);
                    break;

                case "CL":
                    $etat = $doctrine->getRepository(Etat::class)->find(1);
                    break;

                case "DR":
                    $etat = $doctrine->getRepository(Etat::class)->find(2);
                    break;


            }
            $newFicheFrais->setEtat($etat);
            $doctrine->getManager()->persist($newFicheFrais);
            $doctrine->getManager()->flush();

        }

        return $this->render('dataimport/index.html.twig', [
            'controller_name' => 'DataimportController',
        ]);
    }


    #[Route('/importhforfait', name: 'app_data_importforfait')]
    public function importFicheforfait(ManagerRegistry $doctrine): Response
    {
        $lignesfhfjson = file_get_contents("./lignefraishf.json");
        $lignesfhf= json_decode($lignesfhfjson);

        foreach ($lignesfhf as $lignefhf)
        {
            $newLignefhf=new LigneFraisHorsForfait();
            $newLignefhf->setDate(new \DateTime($lignefhf->date));
            $newLignefhf->setMontant($lignefhf->montant);
            $newLignefhf->setLibelle($lignefhf->libelle);
            $user=$doctrine->getRepository(User::class)->findOneBy(['oldId' => $lignefhf->idVisiteur]);
            $fichefrais=$doctrine->getRepository(FicheFrais::class)->findOneBy(['user'=>$user,'mois'=>$lignefhf->mois]);
            $newLignefhf->setFicheFrais($fichefrais);

            $doctrine->getManager()->persist($newLignefhf);
            $doctrine->getManager()->flush();

        }
        return $this->render('dataimport/index.html.twig', [
            'controller_name' => 'DataimportController',
        ]);
    }


        #[Route('/importlignef', name: 'app_data_importlignef')]
    public function importLigneforfait(ManagerRegistry $doctrine): Response
    {
        $lignesfjson = file_get_contents("./lignefraisf.json");
        $lignesf= json_decode($lignesfjson);

        foreach ($lignesf as $lignef)
        {
            $newLignef=new LigneFraisForfait();
            $newLignef->setMois($lignef->mois);
            $newLignef->setQuantite($lignef->quantite);
            $user=$doctrine->getRepository(User::class)->findOneBy(['oldId' => $lignef->idVisiteur]);
            $fichefrais=$doctrine->getRepository(FicheFrais::class)->findOneBy(['user'=>$user,'mois'=>$lignef->mois]);
            $newLignef->setFicheFrais($fichefrais);
            switch ($lignef->idFraisForfait){
                case "ETP":
                    $fraisForfait = $doctrine->getRepository(FraisForfait::class)->find(4);
                    break;

                case "KM":
                    $fraisForfait = $doctrine->getRepository(FraisForfait::class)->find(3);
                    break;

                case "NUI":
                    $fraisForfait = $doctrine->getRepository(FraisForfait::class)->find(1);
                    break;

                case "REP":
                    $fraisForfait = $doctrine->getRepository(FraisForfait::class)->find(2);
                    break;
            }
            $newLignef->setFraisForfait($fraisForfait);
            $doctrine->getManager()->persist($newLignef);
            $doctrine->getManager()->flush();

        }
        return $this->render('dataimport/index.html.twig', [
            'controller_name' => 'DataimportController',
        ]);

    }

}



