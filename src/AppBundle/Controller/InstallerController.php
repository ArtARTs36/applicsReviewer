<?php

namespace AppBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use AppBundle\Service\SiteConfig;

class InstallerController extends MyAdminController
{
    public function init()
    {
        $newConfig = new SiteConfig();


    }
}
