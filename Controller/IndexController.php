<?php

namespace Xigen\Bundle\EntityFilterBundle\Controller;

use App\Service\EntityFilterService;
#use Xigen\Bundle\EntityFilterBundle\Service\EntityFilterService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    public function __construct(\App\Service\EntityFilterService $entityFilter)
    {
        $this->entityFilter = $entityFilter;
    }

    /**
     * @Route("/entity-filter/{entity}", name="index_test")
     */
    public function index($entity)
    {
        $attributes = $this->entityFilter->getEntityAttributes($entity);

        return $this->json($attributes);
    }

    /**
     * @Route("/entity-filter/{entity}/{attribute}", name="index_attribute")
     */
    public function attribute($entity, $attribute)
    {
        $values = $this->entityFilter->getAttributeValues($entity, $attribute);

        return $this->json($values);
    }
}
