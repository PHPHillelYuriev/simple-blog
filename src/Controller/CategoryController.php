<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CategoryController extends Controller
{
    /**
     * @Route("/categories/{category}", name="showPostsByCategory")
     * @ParamConverter("category", options={"mapping": {"category" = "name"}})
     */
    public function showPostsByCategory(Category $category)
    {   
        return $this->render('category/category.html.twig', compact('category'));
    }

    public function categories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/partial/categories.html.twig', compact('categories'));       
    }

}
