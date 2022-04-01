<?php

// Dossier virtuel correspondant à la classe ArticleController
namespace App\Controller;

// Dans la fonction new  on va appeller la classe Article
// Entité article : Getter & Setter titre , description
use App\Entity\Article;
// ArticleType: On va travaille sur le formulaire
// reprennant les propriétés de l'article par ex. pour edit
use App\Form\ArticleType;
// Dans la fonction new  on va appeller le Repository : lecture en BD
// afficher les articles
use App\Repository\ArticleRepository;
// AbstractController on l'utilise pour tout les controlleur 
// Class de symfony qui sont herité par les controlleur
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// classe qui va avoir tout les information de requete HTTP
// POST / GET
use Symfony\Component\HttpFoundation\Request;
// classe qui va renvoyé une reponse HTTP : HTML : render / renderForm
use Symfony\Component\HttpFoundation\Response;
// Les routes sont présente dans les annotation au dessus des fonctions 
use Symfony\Component\Routing\Annotation\Route;

// Route générique
/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    // Route par fonction
    /**
     * @Route("/", name="app_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        // affiche une Response (Objet http : Vue code HTML)
        // Parametre 1 :article/index.html.twig
        // Parametre 2 :variable 'articles' => $articleRepository->findAll()
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        // on instancie la classe article : ca devient un objet
        // on va renseigné ses propriétés
        $article = new Article();
        // on créé un formulaire à partir de la classe ArticleType
        // on va remplir l'entité Article grâce au formulaire

        // :: fait reference en general a tout ce qui est statique dans la classe
        // ::class ... propriété statique par défaut qui affiche le nom de la classe
        $form = $this->createForm(ArticleType::class, $article);
        // On demande au formulaire de recuperer les données 
        // issue de $request
        $form->handleRequest($request);

        // Si le formulaire a bien été saisie 
        if ($form->isSubmitted() && $form->isValid()) {
            // on va utiliser le Repository pour ajouter les informations
            // dans l'entité article
            $articleRepository->add($article);
            // Rediriger vers la fonction index : app_article_index
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        // renvoie le formulaire avec la vue article/new.html.twig
        // et des variable l'entité Article et le formulaire
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }



    /**
     * @Route("/{id}", name="app_article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        // on créé un formulaire a partir du modele ArticleType 
        // qu on va inserer dans l'entité Article
        $form = $this->createForm(ArticleType::class, $article);
        // On demande au formulaire de recuperer les données 
        // issue de $request
        $form->handleRequest($request);

        // si les données sont bien saisi alors
        if ($form->isSubmitted() && $form->isValid()) {
            // on utilise le repository (lien avec la BD) qui va stocker dans l'entité
            $articleRepository->add($article);
            // Redirigé vers la page ou on affiche tout les articles
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        // Les données ne sont pas saisi ou invalide
        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        // On verifie la securité Csrf  qu'il est = entre ce qui est dans l'URL 
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
           // on le supprime grâce au repository
            $articleRepository->remove($article);
        }

        // on renvoie la vue la page d'index app_article_index
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
