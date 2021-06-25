<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CreateArticleController extends AbstractController
{
    /**
    * @Route("/articles/create", name="articles_create")
    */

    public function createArticles(Request $request): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $article = new Articles();
        $form = $this->createForm(ArticleForm::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setName($article->getName());
            $article->setDescription($article->getDescription());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            // ... сделайте любую другую работу - вроде отправки письма и др
            // может, установите "флеш" сообщение об успешном выполнении для пользователя

            return $this->redirectToRoute('articles_show');
        }

        return $this->render(
            'article/create.html.twig',
            array('form' => $form->createView())
        );
        //return new Response('Saved new product with id '.$article->getId());
    }
}