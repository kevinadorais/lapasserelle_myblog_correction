<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    public function index(Article $article, CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            // Récupère tout les commentaire de l'article, trier dans l'ordre croissant
            'commentaires' => $commentaireRepository->findBy(['article' => $article], ['date' => 'ASC']),
            'article' => $article
        ]);
    }

    public function new(Article $article, Request $request, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        // Création d'un formulaire type a partir de l'objet au dessus
        $form = $this->createForm(CommentaireType::class, $commentaire);
        // Ecoute en cas de requête
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajout d'un datetime de maintenant
            $commentaire->setDate(new DateTime());
            // Ajout de l'article à lier avec le commentaire
            $commentaire->setArticle($article);
            // Methode du repository qui s'occupe du persist et flush
            $commentaireRepository->add($commentaire);
            // $em->persist($commentaire);
            // $em->flush();
            // Redirection vers l'article
            return $this->redirectToRoute('myblog_article_show', ['article' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'article' => $article,
            'form' => $form,
        ]);
    }

    public function show(Commentaire $commentaire): Response
    {
        // On récupere le commentaire en paramètre et le rend disponible directement dans la vue
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    public function edit(Commentaire $commentaire, Request $request, CommentaireRepository $commentaireRepository): Response
    {
        // Récuperation dun commentaire en paramètre et création d'un formulaire à partir de ce commentaire, ce qui nous permet de récuperer les infos pré-rempli
        $form = $this->createForm(CommentaireType::class, $commentaire);
        // mise en place d'un écouteur de request
        $form->handleRequest($request);
        // Si le form et soumis et valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Method permettant le persist et flush
            $commentaireRepository->add($commentaire);
            // Redirection à la vue du commentaire
            return $this->redirectToRoute('myblog_commentaire_show', ['commentaire' => $commentaire->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    public function delete(Article $article, Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        // Verification si le CsrfToken est valide (Notions à voir plus tard)
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            // Suppression du commentaire
            $commentaireRepository->remove($commentaire);
        }
        // Redirection vers l'article
        return $this->redirectToRoute('myblog_article_show', ['article' => $article->getId()], Response::HTTP_SEE_OTHER);
    }
}
