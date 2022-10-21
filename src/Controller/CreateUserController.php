<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{

    #[Route('/createuser', name: 'app_create_user')]




        //Attention aux paramètres passés à cette fonction
        //ManagerRegistry $doctrine: il nous sert à accéder aux méthodes de l&#39;ORM Doctrine
        //pour écrire (ou faire persister) et lire les objets dans la base de données
        //UserPasswordHasherInterface $passwordHasher: il nous sert à accéder à la méthode de hachage d&#39;un mot de passe
        //NB: penser à bien déclarer les namespaces ci-dessus correspondant aux classes utilisées, avec use .....

    public function index(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {

        $newUser=new User();
        $newUser->setLogin('elisabeth2');
        $newUser->setPrenom('Windsor');
        $newUser->setPrenom('Elisabeth');
        $newUser->setCp('74000');
        $newUser->setVille('London');
        $newUser->setDateEmbauche(new \DateTime('2022-09-13'));
        $plaintextpassword = 'KingCharles3';
        $hashedpassword = $passwordHasher->hashPassword($newUser, $plaintextpassword);
        $newUser->setPassword($hashedpassword);
        $doctrine->getManager()->persist($newUser);
        $doctrine->getManager()->flush();






        return $this->render('create_user/index.html.twig', [
            'userlogin' => $newUser->getLogin(),
        ]);




    }


}
