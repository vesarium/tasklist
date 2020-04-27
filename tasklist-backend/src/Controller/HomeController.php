<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use App\Entity\Needs;
use App\Entity\Capabilities;

class HomeController extends FOSRestController
{    
    /**
     * Get needs list
     * @Rest\Get("/needs")
     * @return View
     */
    public function getNeeds(): View
    {
        $needs = $this->getDoctrine()->getRepository(Needs::class)->findBy(['user' => $this->getUser()]);
        $filteredNeeds = [];
        foreach ($needs as $need) {
            $filteredNeeds[] = $need->getObject();
        }
        return $this->view(['status' => 'true', 'data' => $filteredNeeds],Response::HTTP_OK);
    }
    
    /**
     * Add new Need
     * @Rest\Post("/needs")
     * @return View
     */
    public function addNeeds(Request $request)
    {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (!empty($postData['title'])) {
            $need = new Needs();
            $need->setUser($this->getUser());
            $need->setTitle($postData['title']);
            $em->persist($need);
            $em->flush();
            return $this->view(['status' => 'true', 'data' => $need->getObject()], Response::HTTP_CREATED);
        } else {
            return $this->view(['status' => 'false', 'errors' => ['Need cannot be blank']], Response::HTTP_BAD_REQUEST);
        }
    }
    
    /**
     * Get capabilities list
     * @Rest\Get("/capabilities")
     * @return View
     */
    public function getCapabilities(): View
    {
        $capabilities = $this->getDoctrine()->getRepository(Capabilities::class)->findBy(['user' => $this->getUser()]);
        $filteredCapabilities = [];
        foreach ($capabilities as $capability) {
            $filteredCapabilities[] = $capability->getObject();
        }
        return $this->view(['status' => 'true', 'data' => $filteredCapabilities],Response::HTTP_OK);
    }
    
    /**
     * Add new Capability
     * @Rest\Post("/capabilities")
     * @return View
     */
    public function addCapabilities(Request $request)
    {
        $postData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        if (!empty($postData['title'])) {
            $capability = new Capabilities();
            $capability->setUser($this->getUser());
            $capability->setTitle($postData['title']);
            $em->persist($capability);
            $em->flush();
            return $this->view(['status' => 'true', 'data' => $capability->getObject()], Response::HTTP_CREATED);
        } else {
            return $this->view(['status' => 'false', 'errors' => ['Capability cannot be blank']], Response::HTTP_BAD_REQUEST);
        }
    }
}
