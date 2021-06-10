<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\SubcategoryType;
use App\Entity\Subcategory;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @param null $idCategory
     * @Route("/category/{idCategory}", name="category_item")
     */
    public function itemCategory($idCategory = null, CategoryRepository $categoryRepository) : Response
    {
        return $this->render('category/item.html.twig', ['category' => $categoryRepository->find($idCategory)]);
    }

    /**
     * @return Response
     * @Route("category/new", name="category_new")
     */
    public function createCategory()
    {
        return new Response('New Category');
    }

    /**
     * @Route("category/edit/{id}", name="category-edit")
     */
    public function editCategory($id = null, CategoryRepository $categoryRepository, SubcategoryRepository $subcategoryRepository, Request $request = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category)
            ->add('save', SubmitType::class)
            ->add('cancel', ButtonType::class)
            ->add('delete', ButtonType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category);
//            foreach ($category->getSubcategories() as $subcategory) {
//                $subcategory->setCategory($category);
//                $entityManager->persist($subcategory);
//            }
            $entityManager->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('category/edit.html.twig', ['form' => $form->createView(), 'category' => $category]);
    }

    /**
     * @Route("category/delete/{id}", name="delete_category")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $category = $categoryRepository->find($id);
        if ($category) {
            $entityManager->remove($category);
            $entityManager->flush();;
        }
        return $this->redirectToRoute('category');

    }

//    /**
//     * @param CategoryRepository $categoryRepository
//     * @Route("category/test", name="category_test")
//     */
//    public function testCategory(CategoryRepository $categoryRepository) {
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $category = $categoryRepository->find(1);
//
//
//        return $this->render('category/test.html.twig');
//    }

}
