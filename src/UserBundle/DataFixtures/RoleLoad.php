<?php

namespace UserBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\UserRole;

class RoleLoad extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roleRepo = $manager->getRepository(UserRole::class);
        $role = $roleRepo->findByRole('ROLE_USER');

        if (!$role) {

            $role = new UserRole();

            $role->setName("ROLE USER");
            $role->setRole("ROLE_USER");
            $role->setCommentAdd(1);

            $manager->persist($role);
            $manager->flush();

        }
    }

}
