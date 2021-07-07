<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Repository\ArticlesRepository;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ArticlesRepository $articleRepository): Response
    {
        $articles = $articleRepository
        ->findAll();

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
}