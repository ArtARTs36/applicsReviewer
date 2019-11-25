<?php

namespace AppBundle\Interfaces;

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

    /**
     * @return Stat
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStat()
    {
        $statRepo = $this->getEntityManager()->getRepository(Stat::class);
        $stats = $statRepo->findBy([], ['id' => 'desc'], 1);
        $this->stat = isset($stats[0]) && ($stats[0] instanceof Stat) ? $stats[0] : null;

        $allowTime = time() - (60 * 60 * 1);

        if (
            $this->stat !== null &&
            $this->stat->getCreated()->getTimestamp() >= $allowTime
        ) {
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

    public function cacheReset()
    {
        $dir = '../var/cache/prod';
        if (file_exists($dir)) {
            $this->recursiveRemove($dir);
        }
    }

    public function recursiveRemove($path)
    {
        if (is_file($path)) {
            return unlink($path);
        }

        if (is_dir($path)) {
            foreach (scandir($path) as $p) {
                if (($p != '.') && ($p != '..')) {
                    $this->recursiveRemove($path . DIRECTORY_SEPARATOR . $p);
                }
            }
            return rmdir($path);
        }
        return false;
    }
}
