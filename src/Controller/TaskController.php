<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="tasks")
     */
    public function index()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/new", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $task = new Task();

        $request->request->get('new_task');

        if($request->get('name') != null){
            $task->setName($request->get('name'));
            $task->setDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }
    }

    /**
     * @Route("/delete/{id}")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/edit/{id}")
     */
    public function update($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $form = $this->createFormBuilder($task)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Task'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setDate(new \DateTime());

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
        ]);


    }
}
