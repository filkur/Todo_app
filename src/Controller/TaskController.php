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
 * @Route("/task", name="task")
 * Class TaskController
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param TaskRepository $taskRepository
     * @return Response
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/create", name="create_task")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            //entity manager
            $em = $this->getDoctrine()->getManager();
            $task->setDone(false);
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('index'));
        }

        //return a response
        //return new Response('Post was created');
        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
