<?php

namespace UserBundle\Model;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use UserBundle\Entity\User;

class ULoginResponse
{
    public $network = null;
    private $linkProfile;
    private $token;
    private $session;
    private $data;

    /**
     * ULoginResponse constructor.
     * @param $token
     * @param SessionInterface $session
     */
    public function __construct($token, $session)
    {
        $this->token = $token;
        $this->session = $session;
        $this->data = $session->get('ulogin_' . $token) ?? null;

        //$this->data = ['identity' => 'http://fwefwefe.ru', 'network' => 'vk', 'u_name' => 'Ukrainsky'];

        $this->linkProfile = $this->data['identity'] ?? null;
        $this->network = $this->data['network'] ?? null;
    }

    /**
     * Создание сущности на основе полученных данных от uLogin
     *
     * @return User|null
     * @throws \Exception
     */
    public function getUser()
    {
        if (is_null($this->data)) {
            return null;
        }

        if (
            !isset($this->data['network']) ||
            is_null($this->data['network']) ||
            is_null($this->data['identity'])
        ) {
            return null;
        }

        $user = new User();

        $user->setUserName($this->data['u_name'] ?? $this->data['first_name']. '-'. time());
        $user->setFirstName($this->data['first_name'] ?? null);
        $user->setLastName($this->data['last_name'] ?? null);
        $user->setUloginIdentity($this->data['identity']);
        $user->setEmail($this->data['email'] ?? null);

        if (isset($this->data['b_date'])) {
            $unixTime = strtotime ($this->data['btime']);
            $dateTime = new \DateTime($unixTime);

            //$user->setBirthday($dateTime);
        }

        return $user;
    }

    public function getLinkProfile()
    {
        return $this->linkProfile;
    }
}
