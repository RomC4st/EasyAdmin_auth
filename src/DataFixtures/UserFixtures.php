<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User; 


class UserFixtures extends Fixture
{
    private $passwordEncoder;

         public function __construct(UserPasswordEncoderInterface $passwordEncoder)
         {
             $this->passwordEncoder = $passwordEncoder;
         }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user ->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword(
                        $user,
                         '54Br*Y781'
                     )); 
        $manager->persist($user);

        $user1 = new User();
        $user1->setEmail('user@gmail.com');
        $user1 ->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordEncoder->encodePassword(
                        $user1,
                         '54Br*Y781'
                     )); 
        $manager->persist($user1);

        $manager->flush();
    }
}
