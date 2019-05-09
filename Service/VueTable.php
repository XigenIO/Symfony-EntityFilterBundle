<?php

namespace Xigen\Bundle\VueBundle\Service;

use Xigen\Bundle\VueBundle\VueEntityInterface;

use Doctrine\ORM\{EntityManagerInterface, Query, Query\QueryException};

class VueTable
{
    const TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    private $entity;

    private $entityClass;

    /**
     * Used to store the first column in the table
     * @var string
     */
    private static $sortColoumn;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->entityClass = "AppBundle\\Entity\\{$entity}";

        // Added backwords compatibility with old Symfony namespacing
        if (\Symfony\Component\HttpKernel\Kernel::MAJOR_VERSION < 4) {
            $this->entityClass = "App\\Entity\\{$entity}";
        }

        return $this;
    }

    public function loadEntityById($id)
    {
        $entity = $this->getRepository()->find($id);
        if (null === $entity) {
            return null;
        }

        $data = [];
        $reflection = new \ReflectionClass($this->entityClass);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $prop) {
            $name = $prop->getName();
            $get = 'get' .  ucfirst($name);
            if (method_exists($entity, $get)) {
                $data[$name] = $entity->$get();
            }

        }

        return $data;
    }

    public function getEntites($attributes): ?array
    {
        if (false === $this->entityExists()) {
            return null;
        }

        $entites = [];
        $repo = $this->getRepository();
        foreach ($repo->findAll() as $entity) {
            $data = [];
            foreach ($attributes as $key) {
                $get = 'get' .  strtoupper($key);
                $value = $entity->$get();
                if ($entity instanceof VueEntityInterface) {
                    $propVal = $entity->__vueProperty($key);

                    if($propVal !== null) {
                        $value = $propVal;
                    }
                }

                if ($value instanceof \DateTime) {
                    $value = $value->format(self::TIME_FORMAT);
                }
                $data['id'] = $entity->getId();
                $data[$key] = $value . '';
            }
            $html = '';


            if (method_exists($repo, 'getVueHtml')) {
                $html = $repo->getVueHtml($entity);
            }
            $data['_html_'] = $html;
            $entites[] = $data;
        }

        // Sort the array by first column alphabetically
        self::$sortColoumn = $attributes[0];
        usort($entites, [$this, 'orderBySortKey']);

        return $entites;
    }

    public function getEntityAttributes(): ?array
    {
        if (false === $this->entityExists()) {
            return null;
        }

        $props = [];
        $reflection = new \ReflectionClass($this->entityClass);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PRIVATE) as $prop) {
            $props[] = $prop->getName();
        }

        return $props;
    }

    public function getAttributeValues($attribute)
    {
        $values = [];
        $repo = $this->getRepository();

        $entity = $this->getEntity();
        $vueRenderCompat = $entity instanceof VueEntityInterface;

        try {
            $query = $repo->createQueryBuilder('e')
                ->select("e.{$attribute}")
                ->where("e.{$attribute} != ''")
                ->getQuery()
            ;

            foreach ($query->getScalarResult() as $row) {
                $value = $row[$attribute];

                if ($vueRenderCompat) {
                    $propVal = $entity->__vueProperty($attribute, $value);

                    if($propVal !== null) {
                        $value = $propVal;
                    }
                }

                $values[$value] = $value;
            }

        } catch (QueryException $e) {
            foreach ($repo->findAll() as $row) {
                $get = 'get' .  ucfirst($attribute);
                $value = $row->$get();

                if ($value instanceof VueEntityInterface) {
                    $value = $value->__toVue();
                }

                if ($value instanceof \DateTime) {
                    $value = $value->format(self::TIME_FORMAT);
                }

                if ($vueRenderCompat) {
                    $propVal = $entity->__vueProperty($attribute, $value);

                    if($propVal !== null) {
                        $value = $propVal;
                    }
                }

                $values[] = $value;
            }
        }

        $values = array_unique($values, SORT_STRING);
        sort($values, SORT_STRING);

        return $values;
    }


    private function entityExists(): bool
    {
        return class_exists($this->getEntityClass());
    }

    private function getEntityClass()
    {
        return $this->entityClass;
    }

    private function getEntity()
    {
        $class = $this->getEntityClass();

        return new $class;
    }

    private function getRepository()
    {
        return $this->em->getRepository($this->getEntityClass());
    }
}
