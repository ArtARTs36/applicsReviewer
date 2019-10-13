<?php

namespace AppBundle\Interfaces;

use AppBundle\Entity\Stat;
use AppBundle\Service\SiteConfig;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Repository\ApplicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    /**
     * @var SiteConfig
     */
    private $siteConfig;

    public function __construct()
    {
        $this->siteConfig = new SiteConfig();
    }

    /**
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function statRefresh()
    {
        /** @var ApplicRepository $applicRepo */
        $applicRepo = $this->getDoctrine()->getManager()->getRepository(Applic::class);
        $applics = $applicRepo->findBy(
            ['status' => Applic::STATUS_NEW],
            ['id' => 'desc'],
            100
        );

        $countApplics = $applicRepo->getCountApplics();

        $countNoProcessApplics = 0;
        $noProcessApplics = [];

        $newStat = new Stat();

        /** @var Applic $applic */
        foreach($applics as $applic) {
            if ($applic->getStatus()->getId() == Applic::STATUS_NEW) {
                $countNoProcessApplics++;
                $noProcessApplics[] = $applic;

                $newStat->addNoProcessApplics($applic);
            }
        }

        $newStat->setCountApplics($countApplics);
        $newStat->setCountNoProcessApplics($countNoProcessApplics);
        $newStat->setCreated(new \DateTime());

        $this->getEntityManager()->persist($newStat);
        $this->getEntityManager()->flush($newStat);

        $newStat->setNoProcessApplics($noProcessApplics);

        $this->stat = $newStat;
    }

    public function getConfig()
    {
        return $this->siteConfig;
    }

    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
