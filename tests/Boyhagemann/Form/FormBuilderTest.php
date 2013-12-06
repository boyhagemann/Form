<?php

namespace Boyhagemann\Form;

class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $fb;

	public function setUp()
	{
		$this->fb = new FormBuilder(new FormElementContainer);
	}

	public function testGetFormElementContainer()
	{
		$this->assertInstanceOf('Boyhagemann\Form\FormElementContainer', $this->fb->getElementContainer());
	}
}