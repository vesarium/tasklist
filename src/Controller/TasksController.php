<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends AbstractController
{
    public function index()
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->getTasksList($this->getUser()->getId());
        if ($this->getUser()->isAdmin()) {
            return $this->render('tasks/manage-tasks.html.twig', ['tasks' => $tasks]);
        }
        return $this->render('tasks/index.html.twig', ['tasks' => $tasks]);
    }
    
    public function myTask(Request $request) {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->getUsersTasksList($this->getUser()->getId());
        return $this->render('tasks/my-tasks.html.twig', ['tasks' => $tasks]);
    }
        
    public function manageTask(Request $request) {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (isset($postData['id'])) {
            if ($postData['id'] == '0') {
                $task = new Task();
                $task->setUser($this->getUser());
            } else {
                $task = $this->getDoctrine()->getRepository(Task::class)->find(intval($postData['id']));
                if (!$task) {
                    $this->addFlash('error', 'Task not found.');
                    return $this->redirectToRoute('my-tasks');
                } else if (($task->getUser() !== $this->getUser()) && !$this->getUser()->isAdmin()) {
                    $this->addFlash('error', 'You can not update other user\'s task.');
                    return $this->redirectToRoute('my-tasks');
                }
            }
            $task->setTitle($postData['title']);
            $task->setDescription($postData['description']);
            $task->setDueDate(\DateTime::createFromFormat('Y-m-d', $postData['due_date']));
            $task->setRespectPoints($postData['respect_points']);
            $task->setStatus("Added");
            $em->persist($task);
            $em->flush();
            $this->addFlash('success', 'Task '.($postData['id'] == '0'? "added": "updated").' successfully.');
        } else {
            $this->addFlash('error', 'Misssing required parameters.');
        }
        return $this->redirectToRoute($this->getUser()->isAdmin()? 'tasks': 'my-tasks');
    }
    
    public function deleteTask($id)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findOneBy(['id' => intval($id)]);
        if ($task) {
            if (($task->getUser() === $this->getUser()) || $this->getUser()->isAdmin()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();
                $this->addFlash('success', 'Task deleted successfully.');
            } else {
                $this->addFlash('error', 'You can not delete other user\'s task.');
            }
        } else {
            $this->addFlash('error', 'Task doesn\'t exists.');
        }
        return $this->redirectToRoute($this->getUser()->isAdmin()? 'tasks': 'my-tasks');
    }
    
    public function acceptTask($id) {
        $task = $this->getDoctrine()->getRepository(Task::class)->findOneBy(['id' => intval($id), 'status' => 'Added']);
        if ($task) {
            if ($task->getUser() !== $this->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $task->setStatus("Accepted");
                $task->setAcceptedBy($this->getUser());
                $em->persist($task);
                $em->flush();
                $this->addFlash('success', 'Task accepted successfully.');
            } else {
                $this->addFlash('error', 'You can not accept your own task.');
            }
        } else {
            $this->addFlash('error', 'Task doesn\'t exists.');
        }
        return $this->redirectToRoute('tasks');
    }
    
    public function finishTask($id) {
        $task = $this->getDoctrine()->getRepository(Task::class)->findOneBy(['id' => intval($id), 'status' => 'Accepted', 'accepted_by' => $this->getUser()]);
        if ($task) {
            if ($task->getUser() !== $this->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $task->setStatus("Finished");
                $task->setAcceptedBy($this->getUser());
                $em->persist($task);
                // Add points to User
                $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                $user->setRespectPoint(intval($user->getRespectPoint()) + intval($task->getRespectPoints()));
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Task finished successfully.');
            } else {
                $this->addFlash('error', 'You can not finish your own task.');
            }
        } else {
            $this->addFlash('error', 'Task doesn\'t exists.');
        }
        return $this->redirectToRoute('tasks');
    }
    
}
