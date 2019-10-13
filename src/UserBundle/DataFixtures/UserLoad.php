<?php

namespace UserBundle\DataFixtures;

use App\DataFixtures\RoleLoad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\UserRole;
use UserBundle\Entity\User;
use UserBundle\Entity\UserAccount;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserLoad extends Fixture
{
    protected $encoder = null;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {

        $roleRepo = $manager->getRepository(UserRole::class);
        $role = $roleRepo->findOneByRole('ROLE_USER');

        if (!$role)

            return;

        $user = new User();
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->addRole($role);
        $user->setUsername ('Test');
        $user->setEmail('info@test.ru');

        $userAccount = new UserAccount();
        $userAccount->setFirstName('John')->setLastName('Doe');
        $userAccount->setBirthday(new \DateTime());

        $manager->persist($user);
        $manager->flush();
        $userAccount->setUser($user);

        $manager->persist($userAccount);
        $manager->flush();

    }

}
