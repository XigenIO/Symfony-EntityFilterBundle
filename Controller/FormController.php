<?php

namespace Xigen\Bundle\VueBundle\Controller;

use Xigen\Bundle\VueBundle\Service\VueForm;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class FormController extends Controller
{
    /**
     * @var \Xigen\Bundle\VueBundle\Service\VueForm
     */
    protected $form;

    public function __construct(VueForm $form)
    {
        $this->form = $form;
    }

    /**
     * @Route("/form/{form}", name="VueForm_index")
     */
    public function form($form)
    {
        $form = $this->form->getForm($form);
        if (null === $form) {
            return $this->json(['error' => 'Unable to load that form']);
        }

        dump($this->form);

        exit();
    }

    /**
     * @Route("/form/save/{entity}/{id}", defaults={"id"=null}, name="VueForm_save")
     */
    public function save($entity, $id, Request $request)
    {
        $save = $this->form->saveEntity($entity, $request, $id);

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/form/delete/{entity}/{id}", requirements={"id"="\d+"}, methods={"DELETE"}, name="VueForm_delete")
     */
    public function delete($entity, $id, Request $request)
    {
        $delete = $this->form->deleteEntity($entity, $id);

        return $this->json(['success' => $delete]);
    }
}
