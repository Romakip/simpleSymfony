<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * @return Response
     * @Route("article/new", name="article_new")
     */
    public function createArticle(Request $request = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article();

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, ['label' => 'Title', 'attr' => ['placeholder' => 'Name of the article']])
            ->add('summary', TextareaType::class, ['label' => 'Article Summary', 'attr' =>
                ['maxlength' => "1000", 'style' => 'height: 5em', 'placeholder' => 'Brief description of the article']])
            ->add('content', TextareaType::class, ['label'=> 'Article Content', 'attr' =>
                ['maxlength' => "100000", 'style' => 'height: 30em', 'placeholder' => 'The HTML content of the article']])
            ->add('category', ChoiceType::class, ['choices' => $categories,
                'choice_label' => function($categories, $key, $index) {
                    return $categories->getName();
                }])
            ->add('publicationDate', TextType::class, ['label' => 'Publication Date'])
            ->add('save', SubmitType::class)
            ->add('cancel', ButtonType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('article');
        }

        return $this->render('article/create.html.twig', ['form' => $form->createView(),  'categories' => $categories]);

    }

    /**
     * @Route("article/edit/{page}", name="edit_article")
     */
    public function editArticle(Request $request = null, $page = null)
    {
        if (isset($page)) {
            $entityManager = $this->getDoctrine()->getManager();
            $categories = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findAll();
            $article = $this->getDoctrine()->getRepository(Article::class)->find($page);
            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, ['label' => 'Title', 'attr' => ['placeholder' => 'Name of the article']])
                ->add('summary', TextareaType::class, ['label' => 'Article Summary', 'attr' =>
                    ['maxlength' => "1000", 'style' => 'height: 5em', 'placeholder' => 'Brief description of the article']])
                ->add('content', TextareaType::class, ['label'=> 'Article Content', 'attr' =>
                    ['maxlength' => "100000", 'style' => 'height: 30em', 'placeholder' => 'The HTML content of the article']])
                ->add('category', ChoiceType::class, ['choices' => $categories,
                    'choice_label' => function($categories, $key, $index) {
                        return $categories->getName();
                    }])
                ->add('publicationDate', TextType::class, ['label' => 'Publication Date'])
                ->add('save', SubmitType::class)
                ->add('cancel', ButtonType::class, ['attr' => ['href' => '/123']])
                ->add('delete', ButtonType::class)
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $article = $form->getData();
                $entityManager->persist($article);
                $entityManager->flush();
                return $this->redirectToRoute('article');
            }
            return $this->render('article/edit.html.twig', ['form' => $form->createView(), 'categories' => $categories, 'id' => $page]);
        }


        return $this->redirectToRoute('article');

    }

    /**
     * @Route("article/{page}", name="show_one_article", requirements={"page"="\d+"})
     */
    public function showOneArticle($page = 1)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($page);
        $category = $article->getCategory();
        if (!$article)
        {
            throw $this->createNotFoundException('Not found articles');
        }

        return $this->render('article/item.html.twig', ['article' => $article, 'category' => $category]);
    }

    /**
     * @param $page
     * @Route("article/delete/{page}", name="article_delete")
     */
    public function deleteArticle($page = null)
    {
        if ($page) {
            $entityManager = $this->getDoctrine()->getManager();
            $article = $this->getDoctrine()->getRepository(Article::class)->find($page);
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('article');
    }


}
