<?php

namespace AppBundle\Helper;

class IconHelper
{
    private $iconClasses = null;

    public function getInversionIconClasses()
    {
        $this->loadIconClasses();

        return array_flip($this->iconClasses);
    }

    public function getClasses()
    {
        $this->loadIconClasses();

        return $this->iconClasses;
    }

    public function getClass($icon)
    {
        $this->loadIconClasses();

        return $this->iconClasses[$icon];
    }

    public function loadIconClasses()
    {
        if (empty($this->iconClasses)) {
            $this->iconClasses = json_decode(file_get_contents('src/AppBundle/Resources/views/icons.json'));
        }
    }
}
