<?php

namespace Xigen\Bundle\VueBundle;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Xigen\Bundle\VueBundle\Annotations\VueRenderProperty;

abstract class VueEntity implements VueEntityInterface
{
    private $cache = false;

    private function getCache()
    {
        if(!$this->cache) {
            $this->cache = new FilesystemCache();
        }

        return $this->cache;
    }

    public function __toVue()
    {
        return $this->__toString();
    }

    private function getAnnotations($property) {
        $reader = new AnnotationReader();
        try {
            $reflProperty = new \ReflectionProperty(get_class($this), $property);
            $classAnnotations = $reader->getPropertyAnnotations($reflProperty);

            return $classAnnotations;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getAnnotation($property, $class)
    {
        $cacheKey = str_replace('\\', '_', get_class($this) . '.' . $class . '__' . $property);
        $cache = $this->getCache();

        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        }

        try {
            $reader = new AnnotationReader();
            $reflProperty = new \ReflectionProperty(get_class($this), $property);

            $annotation = $reader->getPropertyAnnotation($reflProperty, $class);

            $cache->set($cacheKey, $annotation);

            return $annotation;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function __vueProperty($property, $value = null)
    {
        $annotation = $this->getAnnotation($property, VueRenderProperty::class);

        if(!($annotation instanceof VueRenderProperty)) {
            return null;
        }

        if($value === null) {
            $get = 'get' .  strtoupper($property);
            $value = $this->$get();
        }

        switch ($annotation->type) {
            case 'SECONDS_TO_TIME':
                if (!is_int($value)) {
                    break;
                }

                $value = $this->secondsToTime($value);
                break;
            case 'SECONDS_TO_HOURS':
                if (!is_int($value)) {
                    break;
                }

                $value = $this->secondsToHours($value);
                break;
        }

        $dp = $annotation->dp;
        if ($dp !== null && is_int($dp) && (is_int($value) || is_float($value))) {
            $value = number_format($value, $dp, '.', '');
        }


        $dpTrim = $annotation->dpTrim;
        if(is_string($value) && is_bool($dpTrim) && $dpTrim) {
            $value = (string)($value + 0);
        }

        $suffix = $annotation->suffix;
        if(is_string($value) && is_string($suffix)) {
            $value .= $suffix;
        }

        if ($value === null) {
            return null;
        }

        return $value;

    }

    private function secondsToTime($seconds) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }

    private function secondsToHours($seconds) {
        return $seconds / 3600;
    }
}
