<?php

namespace AppBundle\Interfaces;

use AppBundle\Service\SiteConfig;
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

    public function getConfig()
    {
        return $this->siteConfig;
    }

    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
