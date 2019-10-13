<?php

namespace AppBundle\Entity;

use ApplicBundle\Entity\Applic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="statictics")
 */
class Stat
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $countApplics;

    /**
     * @ORM\Column(type="integer")
     */
    private $countNoProcessApplics;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity="\ApplicBundle\Entity\Applic", inversedBy="stat", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="stat_applics_no_process")
     */
    private $noProcessApplics;

    public function __construct()
    {
        $this->noProcessApplics = new ArrayCollection();
    }

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
    public function getCountApplics()
    {
        return $this->countApplics;
    }

    /**
     * @param mixed $countApplics
     */
    public function setCountApplics($countApplics)
    {
        $this->countApplics = $countApplics;
    }

    /**
     * @return mixed
     */
    public function getCountNoProcessApplics()
    {
        return $this->countNoProcessApplics;
    }

    /**
     * @param mixed $countNoProcessApplics
     */
    public function setCountNoProcessApplics($countNoProcessApplics)
    {
        $this->countNoProcessApplics = $countNoProcessApplics;
    }

    /**
     * @return \DateTime
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
    public function getNoProcessApplics()
    {
        return $this->noProcessApplics;
    }

    /**
     * @param mixed $noProcessApplics
     */
    public function setNoProcessApplics($noProcessApplics)
    {
        $this->noProcessApplics = $noProcessApplics;
    }

    public function addNoProcessApplics(Applic $applic)
    {
        $this->noProcessApplics[] = $applic;
    }
}
