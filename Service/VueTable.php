<?php

namespace Xigen\Bundle\VueBundle\Service;

use Doctrine\ORM\{EntityManagerInterface, Query, Query\QueryException};

class VueTable
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    private $entity;

    private $entityClass;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        $this->entityClass = "App\\Entity\\{$entity}";

        return $this;
    }

    public function getEntites($attributes): ?array
    {
        if (false === $this->entityExists()) {
            return null;
        }

        $entites = [];
        foreach ($this->getRepository()->findAll() as $entity) {
            $data = [];
            foreach ($attributes as $key) {
                $get = 'get' .  strtoupper($key);
                $value = $entity->$get();
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i');
                }
                $data[$key] = $value . '';
            }
            $entites[] = $data;
        }

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

        try {
            $query = $repo->createQueryBuilder('e')
                ->select("e.{$attribute}")
                ->where("e.{$attribute} != ''")
                ->getQuery()
            ;

            foreach ($query->getScalarResult() as $row) {
                $values[] = $row[$attribute];
            }
        } catch (QueryException $e) {
            foreach ($repo->findAll() as $row) {
                $get = 'get' .  strtoupper($attribute);
                $value = $row->$get();
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i');
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
