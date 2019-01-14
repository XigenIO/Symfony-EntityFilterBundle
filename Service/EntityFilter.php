<?php

namespace Xigen\Bundle\EntityFilterBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class EntityFilter
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getEntityAttributes($entity): array
    {
        dump($entity);
        exit();
    }
}
