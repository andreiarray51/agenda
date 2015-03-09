<?php

namespace Array51\AgendaBundle\Tests\Service;

use Array51\AgendaBundle\Service\FormService;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Form;
use Array51\DataBundle\Entity\Event;
use Symfony\Component\Form\FormError;

class FormServiceTest extends \PHPUnit_Framework_TestCase
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formFactory = $this->getMockBuilder(
            'Symfony\Component\Form\FormFactory'
        )
            ->setMockClassName('FormFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->form = $this->getMockBuilder(
            'Symfony\Component\Form\Form'
        )
            ->setMockClassName('Form')
            ->setMethods(['submit', 'isValid', 'getErrors', 'all'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSubmitNoErrors()
    {
        $type = 'event';
        $event = new Event();
        $data = [
            'name' => 'Event name',
            'description' => 'Event description',
            'due' => '2015-03-09',
        ];

        $this->form->expects($this->once())
            ->method('submit')
            ->with($data);

        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($type, $event)
            ->will($this->returnValue($this->form));

        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);
        $formService->create($type, $event);
        $formService->submit($data);

        $result = $formService->isValid();

        $this->assertTrue($result);
    }

    public function testSubmitWithErrors()
    {
        $type = 'event';
        $event = new Event();
        $data = [
            'name' => 'Event name',
            'description' => 'Event description',
        ];

        $this->form->expects($this->once())
            ->method('submit')
            ->with($data);

        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $formError = new FormError('Error message');
        $this->form->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue([$formError]));

        $this->form->expects($this->once())
            ->method('all')
            ->will($this->returnValue([]));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($type, $event)
            ->will($this->returnValue($this->form));

        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);
        $formService->create($type, $event);
        $formService->submit($data);

        $result = $formService->isValid();
        $errors = $formService->getErrors();

        $this->assertFalse($result);
        $this->assertEquals(['Error message'], $errors);
    }

    public function testSubmitWithoutCreatingForm()
    {
        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);
        $formService->submit([]);

        $form = $formService->getForm();
        $this->assertNull($form);
    }

    public function testisValidWithoutCreatingForm()
    {
        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);

        $isValid = $formService->isValid();
        $this->assertNull($isValid);
    }

    public function testisValidWithoutSubmit()
    {
        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);
        $formService->create();

        $isValid = $formService->isValid();
        $this->assertNull($isValid);
    }

    public function testisValidWithoutCreatingFormAndSubmit()
    {
        $formService = new FormService();
        $formService->setFormFactory($this->formFactory);

        $isValid = $formService->isValid();
        $this->assertNull($isValid);
    }
}
