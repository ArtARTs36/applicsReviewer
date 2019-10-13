<?php

namespace AppBundle\Interfaces;

use AppBundle\Controller\StatControlVersionAdminControllerTrait;
use AppBundle\Entity\ConfigControlVersion;
use AppBundle\Entity\Stat;
use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\LogApplicChanges;
use Symfony\Component\HttpFoundation\Response;

class MyAdminController extends MyController
{
    /** @var Stat Stat */
    private $stat = null;

    public function addLogApplic($field, Applic $applic, $newValue, $oldValue)
    {
        $getFunc = 'get'. ucfirst($field);

        if (!method_exists($applic, $getFunc)) {
            return false;
        }

        if ($newValue == $oldValue) {
            return false;
        }

        $log = new LogApplicChanges();
        $log->setApplic($applic);
        $log->setField($field);
        $log->setValue($newValue);
        $log->setOldValue($oldValue);
        $log->setCreated(new \DateTime());

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush($log);

        return $log;
    }

    public function statRefresh()
    {
        $applicRepo = $this->getDoctrine()->getManager()->getRepository(Applic::class);
        $applics = $applicRepo->findBy([], ['id' => 'desc'], 100);

        $countApplics = count($applics);

        $countNoProcessApplics = 0;
        $noProcessApplics = [];
        /** @var Applic $applic */
        foreach($applics as $applic) {
            if ($applic->getStatus()->getId() == Applic::STATUS_NEW) {
                $countNoProcessApplics++;
                $noProcessApplics[] = $applic;
            }
        }

        $newStat = new Stat();
        $newStat->setCountApplics($countApplics);
        $newStat->setCountNoProcessApplics($countNoProcessApplics);
        $newStat->setCreated(new \DateTime());

        $this->getEntityManager()->persist($newStat);
        $this->getEntityManager()->flush();

        $newStat->setNoProcessApplics($noProcessApplics);

        $this->stat = $newStat;
    }

    public function getStat()
    {
        if ($this->stat !== null) {
            return $this->stat;
        }

        $this->statRefresh();

        return $this->stat;
    }

    private function getLogs()
    {
        $logApplicRepo = $this->getEntityManager()->getRepository(LogApplicChanges::class);
        $logs = $logApplicRepo->findBy([],[], 10);

        return $logs;
    }

    private function getLogsSettings()
    {
        $logRepo = $this->getEntityManager()->getRepository(ConfigControlVersion::class);
        $logs = $logRepo->findBy([],[],10);

        return $logs;
    }

    public function render($view, array $parameters = [], Response $response = null)
    {
        $parameters['stat'] = $this->getStat();
        $parameters['logs'] = $this->getLogs();
        $parameters['logsSettings'] = $this->getLogsSettings();
        $parameters['user'] = $this->getUser();

        return parent::render($view, $parameters, $response);
    }
}
