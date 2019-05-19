<?php

namespace Xigen\Bundle\VueBundle\PropertyTransformer;

abstract class BaseTransformer implements PropertyTransformerInterface
{
    /**
     * Used to convert the table row output
     * @param $string
     * @return string
     */
    public function transform($string)
    {
        return $string;
    }
}
