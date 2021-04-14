<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use App\Services\Category\UserCategories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task", name="task_")
 * Class TaskController
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/create", name="create_task")
     */
    public function create(Request $request, UserCategories $userCategories): Response
    {
        $categories = $userCategories->getCategories();

        $task = new Task();

        $form = $this->createForm(TaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //entity manager
            $em = $this->getDoctrine()
                       ->getManager()
            ;

            $task->setUser($this->getUser());
            $task->setDone(false);
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('category_index'));
        }

        return $this->render(
            'task/create.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route ("/show/{id}", name="show")
     */
    public function show(Task $task, TaskRepository $taskRepository, Request $request, UserCategories $userCategories): Response
    {
        $categories= $userCategories->getCategories();
        $isDone = $task->isDone();
        if ($isDone) {
            $form = $this->createFormBuilder()
                         ->add(
                             'setDone',
                             SubmitType::class,
                             [
                                 'label' => 'Undone',
                             ]
                         )
                         ->getForm()
            ;
        } else {
            $form = $this->createFormBuilder()
                         ->add(
                             'setDone',
                             SubmitType::class,
                             [
                                 'label' => 'Done',
                             ]
                         )
                         ->getForm()
            ;
        }

        $taskId = $task->getId();

        $task = $taskRepository->findById($taskId);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($task->isDone()) {
                $task->setDone(false);
            } else {
                $task->setDone(true);
            }

            $em = $this->getDoctrine()
                       ->getManager()
            ;

            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'category_show',
                    [
                        'id' => $task->getCategory()
                                     ->getId(),
                        'categories' => $categories,
                    ]
                )
            );
        }

        return $this->render(
            'task/show.html.twig',
            [
                'task' => $task,
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route ("/delete/{id}", name="delete")
     */
    public function remove(Task $task): Response
    {
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $em->remove($task);
        $em->flush();
        $this->addFlash(
            'success',
            'Task was removed'
        );

        return $this->redirect(
            $this->generateUrl(
                'category_index'
            )
        );
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function update(Task $task, Request $request, UserCategories $userCategories): Response
    {
        $categories = $userCategories->getCategories();
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $form = $this->createForm(TaskFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $taskToUpdate = $form->getData();

            $task->setUser($this->getUser());
            $task->setTitle($taskToUpdate->getTitle());
            $task->setDescription($taskToUpdate->getDescription());
            $task->setCategory($taskToUpdate->getCategory());
            $task->setDeadline($taskToUpdate->getDeadline());

            $em->flush();
            $this->addFlash('success', 'Task Updated!');

            return $this->redirect(
                $this->generateUrl(
                    'category_index'
                )
            );
        }

        return $this->render(
            'task/edit.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $categories,
            ]
        );
    }
}
