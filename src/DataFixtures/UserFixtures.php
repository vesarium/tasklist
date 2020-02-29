<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        // Create Admin User
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $manager->persist($user);
        $manager->flush();
                
        for ($i=2; $i<=30; $i++) {
            $user = new User();
            $user->setName('User Name '.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'user'.$i));
            $manager->persist($user);
            $manager->flush();
            
            $task = new Task();
            $task->setUser($user);
            $task->setTitle('Task Title '.$i);
            $task->setDescription('Task Description '.$i);
            $task->setDueDate(new \DateTime());
            $task->setRespectPoints($i * rand(10, 50));
            $task->setStatus('Added');
            $manager->persist($task);
            $manager->flush();
        }
        
    }
}
