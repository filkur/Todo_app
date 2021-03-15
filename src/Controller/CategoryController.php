<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 * Class CategoryController
 *
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render(
            'category/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route ("/show/{id}", name="show")
     */
    public function show(Category $category, TaskRepository $taskRepository): Response
    {
        $categoryId = $category->getId();

        $user = $this->get('security.token_storage')
                     ->getToken()
                     ->getUser()
        ;

        $user->getUsername();

        $userId = $user->getId();

        $tasks = $taskRepository->findAllByCategoryIdAndUserId($categoryId, $userId);

        return $this->render(
            'category/show.html.twig',
            [
                'category' => $category,
                'tasks'    => $tasks,
                'user'     => $user,
            ]
        );
    }
}
