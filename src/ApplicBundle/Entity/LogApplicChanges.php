<?php

namespace ApplicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_applics_changes")
 */
class LogApplicChanges
{
    const FIELD_STATUS = 'status';
    const FIELD_RESULT = 'result';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Applic")
     * @ORM\JoinColumn(name="applic_id", referencedColumnName="id")
     */
    private $applic;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $field;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $oldValue;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

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
    public function getApplic()
    {
        return $this->applic;
    }

    /**
     * @param mixed $applic
     */
    public function setApplic($applic)
    {
        $this->applic = $applic;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param mixed $oldValue
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
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
}
