<?php

namespace AppBundle\Service\SEO;

trait SEOForEntityTrait
{
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $seoKeywords;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $seoDescription;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $seoTitle;

    /**
     * @return mixed
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * @param mixed $seoKeywords
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;
    }

    /**
     * @return mixed
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * @param mixed $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return mixed
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param mixed $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }
}
