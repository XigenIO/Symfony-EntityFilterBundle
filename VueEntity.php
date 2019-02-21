<?php

namespace Xigen\Bundle\VueBundle;

abstract class VueEntity implements VueEntityInterface
{
    public function __toVue()
    {
        return $this->__toString();
    }
}
