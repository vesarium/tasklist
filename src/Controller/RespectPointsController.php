<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Service\ActivityLogService;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ExchangeOffers;

class RespectPointsController extends AbstractController
{
    public function manageRespectPoints(Request $request, ActivityLogService $activityLogService)
    {
        if ($request->isMethod('get')) {
            return $this->render('respect-points/manage-own-rp.html.twig');
        } else {
            $postData = $request->request->all();
            if (isset($postData['respect_points'])) {
                $respect_points = intval($postData['respect_points']);
                if ($respect_points >= 0 && $respect_points <= 1000000) {
                    $em = $this->getDoctrine()->getManager();
                    $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                    $old_rp = $user->getRespectPoint();
                    $user->setRespectPoint($respect_points);
                    $em->persist($user);
                    $em->flush();
                    $activityLogService->logActivity($user, $old_rp, $respect_points);
                    $this->addFlash('success', 'Respect Point updated successfully.');
                } else {
                    $this->addFlash('error', 'Respect Point must be between 0 to 1M');
                }
            } else {
                $this->addFlash('error', 'Missing required parameters');
            }
            return $this->redirectToRoute('manage-respect-points');
        }
    }
    
    public function exchangeRespectPoints(Request $request) {
        if ($request->isMethod('get')) {
            $users = $this->getDoctrine()->getRepository(User::class)->getUsers($this->getUser()->getId());
            return $this->render('respect-points/exchange-rp.html.twig', ['users' => $users]);
        } else {
            $postData = $request->request->all();
            if (isset($postData['to_user']) && isset($postData['respect_points']) && isset($postData['description'])) {
                $respect_points = intval($postData['respect_points']);
                if ($respect_points < $this->getUser()->getRespectPoint()) {
                    $em = $this->getDoctrine()->getManager();
                    $to_user = $this->getDoctrine()->getRepository(User::class)->find($postData['to_user']);
                    $offer = new ExchangeOffers();
                    $offer->setFromUser($this->getUser());
                    $offer->setToUser($to_user);
                    $offer->setRespectPoints($postData['respect_points']);
                    $offer->setDescription($postData['description']);
                    $offer->setStatus('Requested');
                    $offer->setCreatedAt(new \DateTime("now"));
                    $em->persist($offer);
                    $em->flush();
                    $this->addFlash('success', 'Exchange Offer placed successfully.');
                } else {
                    $this->addFlash('error', 'You have only '.$this->getUser()->getRespectPoint().' RP');
                }
            } else {
                $this->addFlash('error', 'Missing required parameters');
            }
            return $this->redirectToRoute('exchange-respect-points');
        }
    }
    
    public function exchangeOfferAccept($id)
    {
        $exchange = $this->getDoctrine()->getRepository(ExchangeOffers::class)->findOneBy(['id' => $id, 'to_user' => $this->getUser()->getId(), 'status' => 'Requested']);
        if ($exchange) {
            $em = $this->getDoctrine()->getManager();
            $exchange->setStatus('Accepted');
            $em->persist($exchange);
            $fromUser = $this->getDoctrine()->getRepository(User::class)->find($exchange->getFromUser()->getId());
            $currentUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
            $fromUser->setRespectPoint(intval($fromUser->getRespectPoint()) - intval($exchange->getRespectPoints()));
            $em->persist($fromUser);
            $currentUser->setRespectPoint(intval($currentUser->getRespectPoint()) + intval($exchange->getRespectPoints()));
            $em->persist($currentUser);
            $em->flush();
            $this->addFlash('success', 'Offer accepted succesfully');
        } else {
            $this->addFlash('error', 'Offer not found');
        }
        return $this->redirectToRoute('homepage');
    }
    
    public function exchangeOfferReject($id)
    {
        $exchange = $this->getDoctrine()->getRepository(ExchangeOffers::class)->findOneBy(['id' => $id, 'to_user' => $this->getUser()->getId(), 'status' => 'Requested']);
        if ($exchange) {
            $em = $this->getDoctrine()->getManager();
            $exchange->setStatus('Rejected');
            $em->persist($exchange);
            $em->flush();
            $this->addFlash('success', 'Offer rejected succesfully');
        } else {
            $this->addFlash('error', 'Offer not found');
        }
        return $this->redirectToRoute('homepage');
    }
}
