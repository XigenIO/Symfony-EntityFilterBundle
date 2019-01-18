<?php

namespace Xigen\Bundle\VueBundle\Controller;

use Xigen\Bundle\VueBundle\Service\EntityFilter;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntityFilterController extends Controller
{
    public function __construct(EntityFilter $entityFilter)
    {
        $this->entityFilter = $entityFilter;
    }

    /**
     * @Route("/entity-filter/{entity}", name="EntityFilter_index")
     */
    public function index($entity)
    {
        $attributes = $this->entityFilter->getEntityAttributes($entity);

        return $this->json($attributes);
    }

    /**
     * @Route("/entity-filter/{entity}/{attribute}", name="EntityFilter_attribute")
     */
    public function attribute($entity, $attribute)
    {
        $values = $this->entityFilter->getAttributeValues($entity, $attribute);

        return $this->json($values);
    }
}
