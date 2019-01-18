<?php

namespace Xigen\Bundle\VueBundle\Controller;

use Xigen\Bundle\VueBundle\Service\EntityTable;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntityTableController extends Controller
{
    public function __construct(EntityTable $entityTable)
    {
        $this->entityTable = $entityTable;
    }

    /**
     * @Route("/entity-table/{entity}", name="EntityTable_index")
     */
    public function index($entity)
    {
        $entites = $this->entityTable->getEntites($entity);

        return $this->json($entites);
    }
}
