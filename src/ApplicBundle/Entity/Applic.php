<?php

namespace ApplicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ApplicBundle\Repository\ApplicRepository")
 * @ORM\Table(name="applics")
 */
class Applic
{
    const STATUS_NEW = 0;
    const STATUS_PROCESS = 1;
    const STATUS_PROCESSED = 2;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $clientName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $clientMail;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $clientPhone;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $IP;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="VocabApplicStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $result;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Stat", mappedBy="noProcessApplics")
     *
     */
    private $stat;

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
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * @param mixed $clientName
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;
    }

    /**
     * @return mixed
     */
    public function getClientMail()
    {
        return $this->clientMail;
    }

    /**
     * @param mixed $clientMail
     */
    public function setClientMail($clientMail)
    {
        $this->clientMail = $clientMail;
    }

    /**
     * @return mixed
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * @param mixed $clientPhone
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;
    }

    /**
     * @return mixed
     */
    public function getIP()
    {
        return $this->IP;
    }

    /**
     * @param mixed $IP
     */
    public function setIP($IP)
    {
        $this->IP = $IP;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return VocabApplicStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getStat()
    {
        return $this->stat;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $stat
     */
    public function setStat($stat)
    {
        $this->stat = $stat;
    }
}