<?php

namespace Xigen\Bundle\VueBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * A way of defining how properties should be rendered
 */
class VueRenderProperty extends Annotation
{
    /**
     * @required
     * @Enum({"SECONDS_TO_TIME", "SECONDS_TO_HOURS"})
     */
    public $type;

    /**
     * @var integer
     */
    public $dp;

    /**
     * @var boolean
     */
    public $dpTrim;
}