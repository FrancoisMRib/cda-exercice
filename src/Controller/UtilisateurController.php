<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UtilisateurRepository ;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\UtilsService;
use Doctrine\ORM\EntityManagerInterface; 

class UtilisateurController extends AbstractController
{
    // #[Route('/utilisateur', name: 'app_utilisateur')]
    // public function index(): Response
    // {
    //     return $this->render('utilisateur/index.html.twig', [
    //         'controller_name' => 'UtilisateurController',
    //     ]);
    // }

    #[Route('/utilisateur/add', name:'app_utilisateur_add')]
    public function addUtilisateur(Request $request, 
    EntityManagerInterface $entityManager,
    UtilisateurRepository $repo): Response 
    {
        $msg = "";
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //nettoyer les données
            $utilisateur->setNom(UtilsService::cleanInput($utilisateur->getNom()));
            $utilisateur->setPrenom(UtilsService::cleanInput($utilisateur->getPrenom()));
            $utilisateur->setEmail(UtilsService::cleanInput($utilisateur->getEmail()));
            $utilisateur->setPassword(UtilsService::cleanInput($utilisateur->getPassword()));
            if($utilisateur->getImage()) {
                $utilisateur->setImage(UtilsService::cleanInput($utilisateur->getImage()));
            }
            //test si le compte n'existe pas
            if(!$repo->findOneBy(["email"=>$utilisateur->getEmail()])) {
                //hasher le password
                $utilisateur->setPassword(md5($utilisateur->getPassword()));
                $entityManager->persist($utilisateur);
                $entityManager->flush();
                $msg = "Le compte " . $utilisateur->getNom() . " a été ajouté en BDD";
            }
            else{
                $msg = "Les informations sont incorrectes";
            }
            
            //dd($article);
            
            }
        return $this->render('utilisateur/index.html.twig', [
            'form' => $form->createView(),
            'message' => $msg,
        ]); 
    }
}
