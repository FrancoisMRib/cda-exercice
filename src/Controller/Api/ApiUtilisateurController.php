<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Utilisateur;
use App\Repository\ArticleRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use App\Entity\User;
// use Symfony\Config\SecurityConfig;


class ApiUtilisateurController extends AbstractController {

    //On stocke dans une variable l'instance de cette classe, qui permet avec le this d'appeler ttes
    //les méthodes stockées dans le repository
    private UtilisateurRepository $utilisateurRepository;
    // private ArticleRepository $articleRepository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $manager;

    // private SecurityConfig $security;
    private UserPasswordHasherInterface $hash;    

    //Utilisation du constructeur
    public function __construct(UtilisateurRepository $utilisateurRepository,
    SerializerInterface $serializer,
    // ArticleRepository$articleRepository,
    // SecurityConfig $security,
    UserPasswordHasherInterface $hash,
    EntityManagerInterface $manager) {
        $this->utilisateurRepository = $utilisateurRepository;

        // $this->articleRepository = $articleRepository;

        $this->serializer = $serializer;

        // $this->security = $security;

        $this->manager = $manager;

        $this->hash = $hash;
    }

    //La route possède un paramètre de plus
    #[Route('/api/utilisateur/all', name:'app_api_utilisateur_all', methods: 'GET')]
    public function getAllUtilisateurs() : Response {
        return $this->json($this->utilisateurRepository->findAll(), 200, [
            //La commande suivante permet de gérer des erreurs
            "Access-Control-Allow-Origin" => "*",
        ], ["groups"=>"api"]);
    }
    //Après ça, ne pas oublier de modifier le ficheir Utilisateur.php en important la classe Groups et en rajoutant les lignes le liant à l'API

    #[Route('api/utilisateur/add', name:'app_api_utilisateur_add', methods:'PUT')]
    public function addUtilisateur(Request $request) : Response 
    {

        //version avec deserialize
        //1 récupérer le contenu de la requête
        $data = $request->getContent();
        //Si le json est valide
        if($data) {
            //convertir le jsone
        $utilisateur = $this->serializer->deserialize($request->getContent(),Utilisateur::class ,"json");
        //hash du password (récupération du password)
        $password = $utilisateur->getPassword();
        //setter le hahsh du password
        //PAS POSSIBLE CAR HASHPASSWORD NICHT MARCHADO : $hash = $this->hash->hashPassword($utilisateur, $utilisateur->getPassword());
        $hash = md5($utilisateur->getPassword());
        //version en 1 ligne
        $utilisateur->setPassword($hash);
        //version en 1 ligne
        //$utilisateur->setPassword($this->hash->hashPassword($utilisateur, $utilisateur->getPassword()));
        
        //3 persister la Categorie
        $this->manager->persist($utilisateur);

        //Flush (enregister en BDD)
        $this->manager->flush();
        $message = $utilisateur;
        $code = 200;
    };


        dd($utilisateur);
        return $this->json($utilisateur, 200,[
            "Access-Control-Allow-Origin" => "*",
        ]);
    }

    #[Route('/api/utilisateur/update', name:'app_api_utilisateur_update', methods:'PUT')]
    public function updateUtilisateur(Request $request):Response {
        $json = $request->getContent();
        if($json){
            //convertir le json en objet utilisateur
            $utilisateur = $this->serializer->deserialize($json, Utilisateur::class, "json");
            //La façon d'écrire explique ce qu'on recherche comme donnée : Utilisateur::class signifie qu'on va faire appel au constructeur de la classe
            //Récupérer le compte utilisateur
            $userRecup = $this->utilisateurRepository->findOneBy(["email"=>$utilisateur->getEmail()]);
            //test si le compte existe
            if($userRecup){
                //setter les nouvelles valeurs
                $userRecup->setNom($utilisateur->getNom());
                $userRecup->setPrenom($utilisateur->getPrenom());
                $userRecup->setPassword(md5($utilisateur->getPassword()));
                $userRecup->setImage($utilisateur->getImage());
                //dd($json);
                //persister les données
                $this->manager->persist($userRecup);
                //enregistre en BDD
                $this->manager->flush();
                $msg = $userRecup;
                $code = 400;
            }
            else {
                $msg = ["Erreur"=>"Les informations sont incorrectes"];
                $code = 400;
            }
            
        }
        else {
            dd("Erreur car le json est invalide") ;
        }
        return $this->json([], 200, ["Access-Control-Allow-Origin" => "*",]);
    }

    #[Route('api/utilisateur/{id}', name:'app_api_utilisateur_delete', methods:'DELETE')]
    public function removeUtilisateur($id, ArticleRepository $articleRepository) : Response
    {
        //récupérer le compte à supprimer
        $utilisateur = $this->utilisateurRepository->find($id);
        if($utilisateur){
            //dd("Le compte existe bien");
            $articles = $articleRepository->findBy(["utilisateur"=>$utilisateur]);
            
            //boucle pour supprimer les articles liés à l'utilisateur
            foreach ($articles as $article) {
                //supprimer les articles
                $this->manager->remove($article);
            }
            //supprimer l'utilisateur
            $this->manager->persist($utilisateur);
            $this->manager->flush();
            //dd("Le compte a été supprimé");
            //message confirmation
            $msg = ["Le compte a bien été supprimé"];
            $code = 200;
        }
        else {
            //dd("Le compte n'existe pas");
            $msg = ["Erreur"=>"Le compte n'existe pas"];
            $code = 400;
        }
        dd($utilisateur);
        return $this->json(["ok"], 200, ["Access-Control-Allow-Origin" => "*",]);
    }

    #[Route('api/utilisateur/{id}', name:'app_api_utilisateur_delete', methods:'DELETE')]
    public function getUtilisateurById($id) : Response
    {
        //récupérer le compte à supprimer
        $utilisateur = $this->utilisateurRepository->find($id);
        if($utilisateur){
            //dd("Le compte existe bien");
            //$articles = $articleRepository->findBy(["utilisateur"=>$utilisateur]);
            
            //boucle pour supprimer les articles liés à l'utilisateur
            // foreach ($articles as $article) {
            //     //supprimer les articles
            //     $this->manager->remove($article);
            // }
            //supprimer l'utilisateur
            // $this->manager->persist($utilisateur);
            // $this->manager->flush();
            //dd("Le compte a été supprimé");
            //message confirmation
            $msg = $utilisateur;
            $code = 200;
        }
        else {
            //dd("Le compte n'existe pas");
            $msg = ["Le compte n'existe pas"];
            $code = 206;
        }
        return $this->json($msg, $code, ["Access-Control-Allow-Origin" => "*"], ["groups" => "api"]);
    }
}
