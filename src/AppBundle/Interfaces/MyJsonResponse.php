<?php

namespace AppBundle\Interfaces;

use Symfony\Component\HttpFoundation\JsonResponse;

class MyJsonResponse
{
    static public function make($success, $msg = null, $objects = null)
    {
        return new JsonResponse([
            'success' => $success,
            'msg' => $msg,
            'obj' => $objects
        ]);
    }
}
