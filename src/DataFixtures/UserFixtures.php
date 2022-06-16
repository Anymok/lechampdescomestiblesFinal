<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('demo');
        $user->setPassword($this->encoder->encodePassword($user, 'demo'));
        $user->setRole('ROLE_ADMIN');
        $manager->persist($user);
        $manager->flush();
    }
}
