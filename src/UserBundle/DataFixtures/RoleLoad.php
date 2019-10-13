<?php

namespace UserBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\UserRole;

class RoleLoad extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $adminRole = new UserRole();

        $adminRole->setName("ROLE USER");
        $adminRole->setRole("ROLE_USER");
        $adminRole->setCommentAdd(1);

        $manager->persist($adminRole);
        $manager->flush($adminRole);

        $adminRole = new UserRole();

        $adminRole->setName("ROLE ADMIN");
        $adminRole->setRole("ROLE_ADMIN");
        $adminRole->setCommentAdd(1);

        $manager->persist($adminRole);
        $manager->flush($adminRole);
    }

}
