<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use App\Entity\Task;
use App\Entity\User;

class TasksController extends FOSRestController
{
    /**
     * Get tasks list
     * @Rest\Get("/tasks")
     * @return View
     */
    public function getTasksList(): View
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->getTasksList($this->getUser()->getId());
        return $this->view(['status' => 'true', 'data' => $tasks], Response::HTTP_OK);
    }
    
    /**
     * Get my tasks list
     * @Rest\Get("/my-tasks")
     * @return View
     */
    public function getMyTasksList(Request $request) {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->getUsersTasksList($this->getUser()->getId());
        return $this->view(['status' => 'true', 'data' => $tasks], Response::HTTP_OK);
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
    
    /**
     * Delete specific task
     * @Rest\Delete("/task/{id}")
     * @return View
     */
    public function deleteTask(int $id): View
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findOneBy(['id' => $id]);
        if ($task) {
            if (($task->getUser() !== $this->getUser()) && !$this->getUser()->isAdmin()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();
                return $this->view(['status' => 'true', 'message' => 'Task deleted successfully'], Response::HTTP_OK);
            }
            return $this->view(['status' => 'false', 'message' => "You can not delete other user's task"], Response::HTTP_BAD_REQUEST);
        }
        return $this->view(['status' => 'false', 'message' => "Task doesn't exists"], Response::HTTP_BAD_REQUEST);
    }
    
    /**
     * Accept specific task
     * @Rest\Get("/accept-task/{id}")
     * @return View
     */
    public function acceptTask(int $id): View
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findOneBy(['id' => intval($id), 'status' => 'Added']);
        if ($task) {
            if ($task->getUser() !== $this->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $task->setStatus("Accepted");
                $task->setAcceptedBy($this->getUser());
                $em->persist($task);
                $em->flush();
                return $this->view(['status' => 'true', 'message' => "Task accepted successfully"], Response::HTTP_OK);
            }
            return $this->view(['status' => 'false', 'message' => "You can not accept your own task"], Response::HTTP_BAD_REQUEST);
       }
       return $this->view(['status' => 'false', 'message' => "Task doesn't exists"], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Finish specific task
     * @Rest\Get("/finish-task/{id}")
     * @return View
     */
    public function finishTask(int $id): View
    {
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
                return $this->view(['status' => 'false', 'message' => "Task finished successfully"], Response::HTTP_OK);
            }
            return $this->view(['status' => 'false', 'message' => "You can not finished your own task"], Response::HTTP_BAD_REQUEST);
        }
        return $this->view(['status' => 'false', 'message' => "Task doesn't exists"], Response::HTTP_BAD_REQUEST);
    }
    
}
