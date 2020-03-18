<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use App\Entity\ActivityLog;

class ActivityLogService {

    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function logActivity($user, $old_rp, $new_rp) {
        $em = $this->container->get('doctrine')->getManager();
        $notes = ($old_rp > $new_rp)? 'Subtracted': (($new_rp > $old_rp)? 'Added': 'No Change');
        $activityLog = new ActivityLog();
        $activityLog->setUser($user);
        $activityLog->setOldRp($old_rp);
        $activityLog->setNewRp($new_rp);
        $activityLog->setNotes($notes);
        $activityLog->setCreatedAt(new \DateTime("now"));
        $em->persist($activityLog);
        $em->flush();
        return true;
    }

}
