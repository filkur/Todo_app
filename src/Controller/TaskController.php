<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
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

            return $this->redirect($this->generateUrl('index'));
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

        //$task = $taskRepository->findBy(array('id' => $taskId)

        $task = $taskRepository->findById($taskId);

        return $this->render(
            'task/show.html.twig',
            [
                'task' => $task,
            ]
        );
    }
}
