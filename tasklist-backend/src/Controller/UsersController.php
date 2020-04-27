<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UsersController extends FOSRestController
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    /**
     * Get users list
     * @Rest\Get("/users")
     * @return View
     */
    public function getUsersList(): View
    {
        $users = $this->getDoctrine()->getRepository(User::class)->getUsersList();
        return $this->view(['status' => 'true', 'data' => $users],Response::HTTP_OK);
    }
    
    /**
     * Add new user
     * @Rest\Post("/users")
     * @return View
     */
    public function postUser(Request $request): View
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = $request->request->all();
        $form->submit($data);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = explode('@', $data['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password[0]));
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();
            return $this->view(['status' => 'true', 'data' => $user->getObject()], Response::HTTP_CREATED);
        }
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $this->view(['status' => 'false', 'errors' => $errors]);        
    }
    
    /**
     * Fetch specific user
     * @Rest\Get("/user/{id}")
     * @return View
     */
    public function showUser(int $id): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if ($user) {
            return $this->view(['status' => 'true', 'data' => $user->getObject()], Response::HTTP_OK);
        }
        return $this->view(['status' => 'false', 'message' => 'User not found'], Response::HTTP_BAD_REQUEST);
    }    
    
    /**
     * Update specific user
     * @Rest\Post("/user/{id}")
     * @return View
     */
    public function updateUser(int $id, Request $request): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if ($user) {
            $form = $this->createForm(UserType::class, $user);
            $data = $request->request->all();
            $form->submit($data);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user->setName($data['name']);
                $user->setEmail($data['email']);
                $em->persist($user);
                $em->flush();
                return $this->view(['status' => 'true', 'data' => $user->getObject()], Response::HTTP_OK);
            }
            $errors = [];
            foreach ($form->getErrors(true, true) as $error) {
                $errors[] = $error->getMessage();
            }
            return $this->view(['status' => 'false', 'errors' => $errors]);
        }
        return $this->view(['status' => 'false', 'message' => 'User not found']);
    }
    
    /**
     * Delete specific user
     * @Rest\Delete("/user/{id}")
     * @return View
     */
    public function deleteUser(int $id): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if ($user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->view(['status' => 'true', 'message' => 'User deleted successfullly'], Response::HTTP_OK);
        }
        return $this->view(['status' => 'false', 'message' => 'User not found'], Response::HTTP_BAD_REQUEST);
    }
}
