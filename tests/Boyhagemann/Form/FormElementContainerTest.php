<?php

namespace Boyhagemann\Form;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Mockery;

class FormElementContainerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormElementContainer
	 */
	protected $container;

	public function setUp()
	{
		$this->container = new FormElementContainer;
	}

	/**
	 * @param $name
	 * @param $class
	 * @dataProvider elementProvider
	 */
	public function testContainerContainsDefaultElements($name, $class)
	{
		$this->assertInstanceOf($class, $this->container->make($name));
	}

	public function elementProvider()
	{
		$elements = array();
		foreach($this->getElements() as $name => $class) {
			$elements[] = array($name, $class);
		}

		return $elements;
	}

	protected function getElements()
	{
		return array(
			'text' 			=>	'Boyhagemann\Form\Element\Text',
			'hidden' 		=>	'Boyhagemann\Form\Element\Hidden',
			'password' 		=>	'Boyhagemann\Form\Element\Password',
			'textarea' 		=>	'Boyhagemann\Form\Element\Textarea',
			'select' 		=> 	'Boyhagemann\Form\Element\Select',
			'modelSelect' 	=>	'Boyhagemann\Form\Element\ModelSelect',
			'checkbox' 		=> 	'Boyhagemann\Form\Element\Checkbox',
			'modelCheckbox' =>	'Boyhagemann\Form\Element\ModelCheckbox',
			'radio' 		=> 	'Boyhagemann\Form\Element\Radio',
			'modelRadio' 	=> 	'Boyhagemann\Form\Element\ModelRadio',
		);
	}
}