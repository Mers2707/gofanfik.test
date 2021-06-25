<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }


}
