<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UsersController extends AbstractController
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->getUsersList();
        return $this->render('users/index.html.twig', [
            'users' => $users
        ]);
    }
    
    public function manageUser(Request $request) {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (isset($postData['id'])) {
            if ($postData['id'] == '0') {
                $user = new User();
                $password = explode('@', $postData['email']);
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password[0]));
            } else {
                $user = $this->getDoctrine()->getRepository(User::class)->find(intval($postData['id']));
            }
            $user->setName($postData['name']);
            $user->setEmail($postData['email']);
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User '.($postData['id'] == '0'? "added": "updated").' successfullly.');
        } else {
            $this->addFlash('error', 'Misssing required parameters.');
        }
        return $this->redirectToRoute('users');
    }
    
    public function deleteUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(intval($id));
        if ($user) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'User deleted successfullly.');
        } else {
            $this->addFlash('error', 'User not found.');
        }
        return $this->redirectToRoute('users');
    }
    
}
