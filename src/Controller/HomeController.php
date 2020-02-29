<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Needs;
use App\Entity\Capabilities;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function index()
    {
        return $this->render('home/index.html.twig');
    }
    
    public function profile() {
        $needs = $this->getDoctrine()->getRepository(Needs::class)->findBy(['user' => $this->getUser()]);
        $capabilities = $this->getDoctrine()->getRepository(Capabilities::class)->findBy(['user' => $this->getUser()]);
        return $this->render('home/profile.html.twig', [
            'needs' => $needs,
            'capabilities' => $capabilities
        ]);
    }
    
    public function addNeed(Request $request) {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (isset($postData['title'])) {
            $need = new Needs();
            $need->setUser($this->getUser());
            $need->setTitle($postData['title']);
            $em->persist($need);
            $em->flush();
            $this->addFlash('success', 'Need added successfully.');
        } else {
            $this->addFlash('error', 'Misssing required parameters.');
        }
        return $this->redirectToRoute('user-profile');
    }
    
    public function addCapability(Request $request) {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (isset($postData['title'])) {
            $capability = new Capabilities();
            $capability->setUser($this->getUser());
            $capability->setTitle($postData['title']);
            $em->persist($capability);
            $em->flush();
            $this->addFlash('success', 'Capability added successfully.');
        } else {
            $this->addFlash('error', 'Misssing required parameters.');
        }
        return $this->redirectToRoute('user-profile');
    }
}
