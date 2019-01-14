<?php

namespace Xigen\Bundle\EntityFilterBundle\Controller;

use Xigen\Bundle\EntityFilterBundle\Service\EntityFilter;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{

    public function __construct(EntityFilter $entityFilter)
    {
        $this->entityFilter = $entityFilter;
    }

    /**
     * @Route("/entity-filter/{entity}", name="entityFilter_index")
     */
    public function index(string $entity)
    {
        $this->entityFilter->getEntityAttributes($entity);
    }
}
