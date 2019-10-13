<?php

namespace ApplicBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ApplicRepository extends EntityRepository
{
    /**
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountApplics()
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('count(a.id)');

        return $qb->getQuery()->getSingleScalarResult();
    }
}
