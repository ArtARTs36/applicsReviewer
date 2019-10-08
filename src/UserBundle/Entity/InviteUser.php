<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_invites")
 */
class InviteUser
{
    const DEFAULT_LIVE = '12';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $key;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * Время жизни инвайта в часах
     *
     * @ORM\Column(type="integer")
     */
    private $live;

    /**
     * Активен ли еще инвайт:
     * 1 - да, не был использован
     * 2 - нет, был использован
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return \DateTime mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * @param mixed $live
     */
    public function setLive($live)
    {
        $this->live = $live;
    }

    /**
     * @return boolean
     * @throws \Exception
     */
    public function isLive()
    {
        $curDate = new \DateTime();
        $maxLiveDate = $this->getCreated()->add(
            new \DateInterval($this->getLive() . 'H')
        );

        return $maxLiveDate > $curDate;
    }

    /**
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
}
