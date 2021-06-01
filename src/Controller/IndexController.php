<?php


namespace App\Controller;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Article;

class IndexController extends AbstractController
{
    public function index()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        if (!$articles)
        {
            throw $this->createNotFoundException('Not found articles');
        }

        return $this->render('index/homepage.html.twig', ['articles' => $articles, 'categories' => $categories]);
    }
}