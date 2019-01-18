<?php

namespace Xigen\Bundle\VueBundle\Service;

use Doctrine\ORM\{EntityManagerInterface, Query};

class EntityTable
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getEntites($entity): ?array
    {
        if (false === $this->entityExists($entity)) {
            return null;
        }

        $query = $this->getRepository($entity)
            ->createQueryBuilder('e')
            ->select("e")
            ->getQuery()
        ;

        return array_values($query->getArrayResult());
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
