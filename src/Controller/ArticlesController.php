<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ArticleSection;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleForm;
use App\Form\ArticleEditForm;
use Doctrine\Common\Collections\ArrayCollection;


class ArticlesController extends AbstractController
{
    /**
    * @Route("/articles", name="articles_show")
    */

    public function show(ArticlesRepository $articleRepository): Response
    {
        $articles = $articleRepository
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
        return $this->render('articles/index.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/articles/{page}", name="articles_readmore", requirements={"page"="\d+"})
     */
    public function readmore(int $page, ArticlesRepository $articleRepository): Response
    {
        $articles = $articleRepository
            ->findBy(array("id" => $page));

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
        return $this->render('/base.html.twig', [
            'controller_name' => 'MainController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/{page}/edit", name="articles_edit", requirements={"page"="\d+"})
     */
    public function edit(Request $request, int $page, ArticlesRepository $articleRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Articles::class)->find($page);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }

        $originalSection = new ArrayCollection();

        foreach ($article->getSectionId() as $section) {
            $originalSection->add($section);
        }
        
        $form = $this->createForm(ArticleForm::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setName($article->getName());
            $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();
            $article->setUserId($nowUser->getId());
            $article->setDescription($article->getDescription());
            $article->setFanfik($article->getFanfik());

            foreach ($originalSection as $section) {
                if (false === $article->getSectionId()->contains($section)) {
                    $section->getArticle()->removeSectionId($section);
                    $section->setArticle(null);
                    $em->persist($section);
                    $em->remove($section);
                }
            }

            $newSection = new ArrayCollection();

            foreach ($article->getSectionId() as $section) {
                $newSection->add($section);
            }

            foreach ($newSection as $section) {
                $section->setArticle($article);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Article is change!');
        }
        return $this->render(
            'articles/create.html.twig',
            array('form' => $form->createView())
        );
    }
}
