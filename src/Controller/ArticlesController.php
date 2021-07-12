<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\ArticleSection;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleForm;
use App\Form\CommentForm;
use Doctrine\Common\Collections\ArrayCollection;


class ArticlesController extends AbstractController
{
    /**
    * @Route("/articles", name="articles_show")
    */

    public function show(ArticlesRepository $articleRepository): Response
    {
        $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if(in_array('ROLE_ADMIN',$nowUser->getRoles())){
            $articles = $articleRepository
            ->findAll();
        } else {
            $articles = $articleRepository
            ->findBy(array("user_id" => $nowUser->getId()));
        }

        if (!$articles) {
            return $this->redirectToRoute('index');
        }
        return $this->render('articles/index.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/{page}", name="articles_readmore", requirements={"page"="\d+"})
     */
    public function readmore(int $page, Request $request, ArticlesRepository $articleRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Articles::class)->find($page);

        $comments = $article->getComments();
        $originalSection = new ArrayCollection();

        foreach ($article->getSectionId() as $section) {
            $originalSection->add($section);
        }

        $nowUser = $this->container->get('security.token_storage')->getToken();
        if(!is_null($nowUser)){
            $authUser = $nowUser->getUser()->getId();
        }

        if(isset($authUser)){
            $comment = new Comments;
            $form = $this->createForm(CommentForm::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setArticle($article);
                $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();
                $comment->setUser($nowUser);
                $comment->setUsername($nowUser->getUsername());
                $comment->setDatetime();

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Comment is add!');
            }
            return $this->render('articles/readmore.html.twig', [
                'article' => $article,
                'form' => $form->createView(),
                'sections' => $originalSection,
                'comments' => $comments,
            ]);
        } else {
            return $this->render('articles/readmore.html.twig', [
                'article' => $article,
                'sections' => $originalSection,
                'comments' => $comments,
            ]);
        }
    }

    /**
     * @Route("/articles/{page}/edit", name="articles_edit", requirements={"page"="\d+"})
     */
    public function edit(Request $request, int $page, ArticlesRepository $articleRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Articles::class)->find($page);

        $validUser = $article->getUserId();
        $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if(in_array('ROLE_ADMIN',$nowUser->getRoles()) || $nowUser->getId()===$validUser){

            $originalSection = new ArrayCollection();

            foreach ($article->getSectionId() as $section) {
                $originalSection->add($section);
            }
            
            $form = $this->createForm(ArticleForm::class, $article);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $article->setName($article->getName());
                $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();
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
        } else {
            $this->addFlash('error', "You don't have access");
            $strPage = (string) $page;
            return $this->redirectToRoute('index');
        }
    }

    /**
    * @Route("/articles/{page}/delete", name="article_delete", requirements={"page"="\d+"})
    */
    public function deleteAction(int $page, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Articles::class)->find($page);
        $validUser = $article->getUserId();

        $nowUser = $this->container->get('security.token_storage')->getToken()->getUser();

        if(in_array('ROLE_ADMIN',$nowUser->getRoles()) || $nowUser->getId()===$validUser){
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', 'Article is delete!');
        }
        return $this->redirectToRoute('articles_show');
    }
}
