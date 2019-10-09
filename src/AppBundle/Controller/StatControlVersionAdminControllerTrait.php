<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ConfigControlVersion;
use Doctrine\ORM\EntityRepository;

trait StatControlVersionAdminControllerTrait
{
    public function getAllConfig()
    {
        /** @var EntityRepository $configRepo */
        $configRepo = $this->getEntityManager()->getRepository(ConfigControlVersion::class);
        $configs = $configRepo->findAll();

        $settings = [];

        /** @var ConfigControlVersion $config */
        foreach ($configs as $config) {
            $arrSettings = json_decode($config->getSettings(), true);
            foreach ($arrSettings as $key => $value) {
                $settings[$this->getConfig()->getDescription($key)] = $value;
            }

            $config->setRead($settings);

            $settings = [];
        }

        return $configs;
    }

    public function revertConfigAction($id)
    {
        $configRepo = $this->getEntityManager()->getRepository(ConfigControlVersion::class);
        $config = $configRepo->find($id);

        $this->getConfig()->revert($config->getSettings());

        return $this->redirectToRoute('admin_settings');
    }

}