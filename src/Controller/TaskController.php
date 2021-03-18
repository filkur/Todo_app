<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function create(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
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
            ]
        );
    }

    /**
     * @Route ("/show/{id}", name="show")
     */
    public function show(Task $task, TaskRepository $taskRepository): Response
    {
        $taskId = $task->getId();

        $task = $taskRepository->findById($taskId);

        return $this->render(
            'task/show.html.twig',
            [
                'task' => $task,
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
    public function update(Task $task, Request $request): Response
    {
        $em = $this->getDoctrine()
                   ->getManager()
        ;

        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
            ]
        );
    }
}
