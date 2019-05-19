<?php

namespace Xigen\Bundle\VueBundle\PropertyTransformer;

use Doctrine\ORM\PersistentCollection;

class ArrayCountableTransformer extends BaseTransformer
{
    /**
     * Used to convert the table row output to a string
     * @param $collection
     * @return string
     */
    public function transform($collection)
    {
        $value = count($collection->toArray());

        return (string) $value;
    }
}
