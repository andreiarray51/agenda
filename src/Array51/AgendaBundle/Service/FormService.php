<?php

namespace Array51\AgendaBundle\Service;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;

class FormService
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Form
     */
    private $form;

    /**
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string|object $type
     * @param object $data
     * @return $this
     */
    public function create($type = 'form', $data = null)
    {
        $this->form = $this->formFactory->create($type, $data);

        return $this;
    }

    /**
     * @param array $data
     * @param bool $clearMissing
     * @return $this
     */
    public function submit($data, $clearMissing = true)
    {
        if ($this->form) {
            $this->form->submit($data, $clearMissing);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isValid()
    {
        if ($this->form) {
            return $this->form->isValid();
        }

        return null;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->getFormErrors($this->form);
    }

    /**
     * @param Form $form
     * @return array
     */
    private function getFormErrors($form)
    {
        $errors = [];

        if ($form instanceof Form) {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            foreach ($form->all() as $key => $child) {
                /** @var $child Form */
                if ($err = $this->getFormErrors($child)) {
                    $errors[$key] = $err;
                }
            }
        }

        return $errors;
    }
}
