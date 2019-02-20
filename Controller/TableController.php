<?php

namespace Xigen\Bundle\VueBundle\Controller;

use Xigen\Bundle\VueBundle\Service\VueTable;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableController extends Controller
{
    /**
     * @var \Xigen\Bundle\VueBundle\Service\VueTable
     */
    protected $table;

    public function __construct(VueTable $table)
    {
        $this->table = $table;
    }

    /**
     * @Route("/table/{entity}/{attributes}", name="VueTable_index")
     */
    public function table($entity, $attributes)
    {
        $attributes = explode('-', $attributes);
        $entites = $this->table->setEntity($entity)
            ->getEntites($attributes)
        ;

        return $this->json($entites);
    }

    /**
     * @Route("/table/filter/{entity}/{attribute}", name="VueTable_filterByAttribute")
     */
    public function filterByAttribute($entity, $attribute)
    {
        $values = $this->table->setEntity($entity)
            ->getAttributeValues($attribute)
        ;

        return $this->json($values);
    }
}
