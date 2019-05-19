<?php

namespace Xigen\Bundle\VueBundle\PropertyTransformer;

class StringProperty
{
    /**
     * Used to convert the table row output to a string
     * @param $string
     * @return string
     */
    public function transform($string)
    {
        return (string) $string;
    }
}
