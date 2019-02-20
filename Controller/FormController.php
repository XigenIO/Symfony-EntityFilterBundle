<?php

namespace Xigen\Bundle\VueBundle\Controller;

use Xigen\Bundle\VueBundle\Service\VueForm;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends Controller
{
    /**
     * @var \Xigen\Bundle\VueBundle\Service\VueForm
     */
    protected $table;

    public function __construct(VueForm $table)
    {
        $this->table = $table;
    }

    /**
     * @Route("/form/{form}", name="VueForm_index")
     */
    public function table($form)
    {
        $form = $this->table->getForm($form);
        if (null === $form) {
            return $this->json(['error' => 'Unable to load that form']);
        }

        dump($this->table);

        exit();
    }
}
