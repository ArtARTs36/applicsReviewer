<?php

namespace AppBundle\Interfaces;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyController extends Controller
{
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }
}
