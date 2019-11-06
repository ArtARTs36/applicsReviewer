<?php

namespace AppBundle\Entity;

use AppBundle\Helper\IconHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * Модель для услуг дел
 *
 * @ORM\Entity
 * @ORM\Table(name="works_services")
 */
class WorkService
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Work", inversedBy="services")
     * @ORM\JoinColumn(name="works_id", referencedColumnName="id")
     */
    private $work;

    /**
     * @ORM\Column(type="integer", length=1000)
     */
    private $icon = null;

    private $iconClass = null;

    private $iconHelper;

    public function __construct()
    {
        $this->iconHelper = new IconHelper();

        if ($this->icon !== null) {
            $this->iconClass = $this->iconHelper->getClass($this->icon);
        }
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @param mixed $work
     */
    public function setWork($work)
    {
        $this->work = $work;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIconClass()
    {
        if ($this->iconClass === null && $this->icon !== null) {
            $this->iconClass = $this->iconHelper->getClass($this->icon);
        }

        return $this->iconClass;
    }
}
