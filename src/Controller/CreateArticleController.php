<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class CreateArticleController extends AbstractController
{
    /**
    * @Route("/articles/create", name="articles_create")
    */

    public function createArticles(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticleForm::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setName($article->getName());
            $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();
            $article->setUserId($nowUser->getId());
            $article->setDescription($article->getDescription());

            $newSection = new ArrayCollection();

            foreach ($article->getSectionId() as $section) {
                $newSection->add($section);
            }

            foreach ($newSection as $section) {
                $section->setArticle($article);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('articles_show');
        }

        return $this->render(
            'articles/create.html.twig',
            array('form' => $form->createView())
        );
    }
    
}