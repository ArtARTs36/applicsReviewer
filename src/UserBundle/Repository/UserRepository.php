<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)->setParameter('email', $username)
            ->getQuery()->getOneOrNullResult();
    }

    public function findByIdentity($identity)
    {
        return $this->createQueryBuilder('u')
            ->where('u.uloginIdentity = :identity')
            ->setParameter('identity', $identity)
            ->getQuery()->getOneOrNullResult();
    }
}
