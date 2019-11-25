<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Service\SEO\SEOForEntityTrait;

/**
 * Модель для хранения метаданных о странице
 *
 * @ORM\Entity
 * @ORM\Table(name="pages_metadata")
 */
class PageMetaData
{
    use SEOForEntityTrait;

    const ID_HOMEPAGE = 1;
    const ID_OFFER_DOCUMENT_ALL = 2;
    const ID_COURT_PRACTICES_ALL = 3;
    const ID_GUEST_BOOK_ALL = 4;
    const ID_CALL_BACK = 5;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

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
}
