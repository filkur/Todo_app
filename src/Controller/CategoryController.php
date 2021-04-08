<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;
use App\Services\Category\UserCategories;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(CategoryRepository $categoryRepository, UserCategories $userCategories): Response
    {
        $categories = $userCategories->getCategories();

        return $this->render(
            'category/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route("/create", name="create_category")
     */
    public function create(Request $request, UserCategories $userCategories): Response
    {
        $categories = $userCategories->getCategories();

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //entity manager
            $em = $this->getDoctrine()
                       ->getManager()
            ;
            $category->setCreatedAt(new \DateTime());
            $category->setUser($this->getUser());
            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('category_index'));
        }

        return $this->render(
            'category/create.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route ("/show/{id}", name="show")
     */
    public function show(Category $category, TaskRepository $taskRepository, UserCategories $userCategories ): Response
    {
        $categories = $userCategories->getCategories();

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
                'categories' => $categories
            ]
        );
    }
}
