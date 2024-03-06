<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\ArtsService;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface; 

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository) 
    {
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'app_article_all')]
    public function articleAll(): Response
    {
        $articles = $this->articleRepository->findAll();

        return $this->render('article/article_all.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/id/{id}',name:'app_article_id')]
    public function articleById($id) : Response 
    {   
        $article = $this->articleRepository->find($id);

        return $this->render('article/article_detail.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/add', name:'app_article_add')]
    public function addArticle(Request $request, 
    EntityManagerInterface $entityManager,
    ArticleRepository $repo): Response
    {
        $msg="";
        $article = new Article() ;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //nettoyer les données
            $article->setTitre(ArtsService::cleanInput($article->getTitre()));
            $article->setContenu(ArtsService::cleanInput($article->getContenu()));

            if($article->getImage()) {
                $article->setImage(ArtsService::cleanInput($article->getImage()));
            }
            //test si le compte n'existe pas
            // if(!$repo->findOneBy(["email"=>$article->getEmail()])) {
                //hasher le password
                //$article->setPassword(md5($article->getPassword()));
                $entityManager->persist($article);
                $entityManager->flush();
                $msg = "L'article " . $article->getTitre() . " a été ajouté en BDD";
            // }
            // else{
            //     $msg = "Les informations sont incorrectes";
            // }
            
            //dd($article);
            
            }
        return $this->render('article/index.html.twig', [
            'form' => $form->createView(),
            'message' => $msg,
        ]); 
    }
}