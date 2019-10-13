<?php

namespace ApplicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="offer_documents")
 */
class OfferDocument
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=100)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="OfferDocumentRequiredDoc", inversedBy="offers", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="offer_documents_required_docs_many")
     */
    private $requiredDocuments;

    /**
     * @ORM\ManyToMany(targetEntity="OfferDocumentDeliveryMethod", inversedBy="offers", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="offer_documents_delivery_methods_many")
     */
    private $deliveryMethods;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->requiredDocuments = new ArrayCollection();
        $this->deliveryMethods = new ArrayCollection();
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRequiredDocuments()
    {
        return $this->requiredDocuments;
    }

    /**
     * @param mixed $requiredDocuments
     */
    public function setRequiredDocuments($requiredDocuments)
    {
        $this->requiredDocuments = $requiredDocuments;
    }

    /**
     * @return mixed
     */
    public function getDeliveryMethods()
    {
        return $this->deliveryMethods;
    }

    /**
     * @param mixed $deliveryMethods
     */
    public function setDeliveryMethods($deliveryMethods)
    {
        $this->deliveryMethods = $deliveryMethods;
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
