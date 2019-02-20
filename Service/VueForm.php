<?php

namespace Xigen\Bundle\VueBundle\Service;

use Doctrine\ORM\{EntityManagerInterface, Query};
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Form\{Forms, AbstractType};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class VueForm
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * @var \Symfony\Component\Form\FormTypeInterface
     */
    private $form;

    /**
     * @var ArrayCollection
     */
    private $fields;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->fields = new ArrayCollection;
    }

    public function getForm($name)
    {
        $this->setName($name);
        if (false === $this->formExists()) {
            return null;
        }

        $this->class = $this->getFormClass();
        $this->form = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory()
            ->create($this->class)
        ;

        //$this->form->handleRequest(Request::createFromGlobals());
        foreach ($this->form->all() as $field) {
            /** @var FormInterface $field */

            /** @var FormConfigInterface $config */
            $config = $field->getConfig();

            /** @var AbstractType */
            $type = $config->getType()->getInnerType();

            /** @var string $name */
            $name = $config->getName();

            /** @var string $component */
            $component = $this->getVueComponentName($type);

            $this->fields->add([
                'name' => $name,
                'component' => $component
            ]);
        }

        return $this->form;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    private function getVueComponentName(AbstractType $type)
    {
        $component = '';
        switch (get_class($type)) {
            case 'Symfony\Component\Form\Extension\Core\Type\ButtonType':
            case 'Symfony\Component\Form\Extension\Core\Type\SubmitType':
                $component = 'b-form-button';
                break;

            case 'Symfony\Component\Form\Extension\Core\Type\TextType':
                $component = 'b-form-input';
                break;

                case 'Symfony\Component\Form\Extension\Core\Type\ImageType':
                case 'Symfony\Component\Form\Extension\Core\Type\FileType':
                $component = 'b-form-file';
                break;
        }

        return $component;
    }

    private function formExists(): bool
    {
        return class_exists($this->getFormClass($this->name));
    }

    private function getFormClass()
    {
        return "App\\Form\\{$this->name}";
    }
}
