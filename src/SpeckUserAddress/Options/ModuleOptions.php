<?php

namespace SpeckUserAddress\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $__strictMode__ = false;

    protected $indexRoute = 'zfcuser/address';

    public function getIndexRoute()
    {
        return $this->indexRoute;
    }

    public function setIndexRoute($indexRoute)
    {
        $this->indexRoute = $indexRoute;
        return $this;
    }
}
