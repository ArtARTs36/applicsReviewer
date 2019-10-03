<?php

namespace AppBundle\Interfaces;

use Symfony\Component\HttpFoundation\JsonResponse;

class MyJsonResponse
{
    private $success;

    private $msg;

    public function __construct($success, $msg)
    {
        $this->success = $success;
        $this->msg = $msg;

        return new JsonResponse([
        'success' => $this->success,
        'msg' => $this->msg
    ]);
    }
}
