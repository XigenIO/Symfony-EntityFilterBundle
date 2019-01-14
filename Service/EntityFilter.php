<?php

namespace Xigen\Bundle\EntityFilterBundle\Service;

use Doctrine\ORM\{EntityManagerInterface, Query};

class EntityFilterService
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getEntityAttributes($entity): ?array
    {
        if (false === $this->entityExists($entity)) {
            return null;
        }

        $query = $this->getRepository($entity)
            ->createQueryBuilder('e')
            ->select("e")
            ->setMaxResults(1)
            ->getQuery()
        ;

        $attributes = [];
        foreach ($query->getArrayResult()[0] as $key => $value) {
            $attributes[] = $key;
        }

        return array_values($attributes);
    }

    public function getAttributeValues($entity, $attribute)
    {
        $repo = $this->getRepository($entity);

        $query = $repo->createQueryBuilder('e')
            ->select("e.id, e.{$attribute}")
            ->where("e.{$attribute} != ''")
            ->getQuery()
        ;

        $values = [];
        foreach ($query->getScalarResult() as $row) {
            $values[$row['id']] = $row[$attribute];
        }

        return $values;
    }

    private function entityExists($name): bool
    {
        return class_exists("App\\Entity\\{$name}");
    }

    private function getEntityClass($name)
    {
        return "App\\Entity\\{$name}";
    }

    private function getEntity($name)
    {
        $class = $this->getEntityClass($name);

        return new $class;
    }

    private function getRepository($name)
    {
        return $this->em->getRepository($this->getEntityClass($name));
    }
}
