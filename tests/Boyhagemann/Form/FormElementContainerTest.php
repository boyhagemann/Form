<?php

namespace Boyhagemann\Form;

use Mockery as m;

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
    
    public function testContainerExtendsContainer()
    {
        $this->mockConnection();
        $this->assertInstanceOf('Illuminate\Container\Container', $this->container);
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
    
    
    /**
    * Mock some of the internals of DB connection
    * used for proper DateTime format. Assuming MySQL, but
    * any can be used, as long as its DateTIme format is consistent
    */
    protected function mockConnection()
    {        
        $model = m::mock('Illuminate\Database\Eloquent\Model');
        
        $model->shouldReceive('setConnectionResolver')->with($resolver = m::mock('Illuminate\Database\ConnectionResolverInterface'));
        $resolver->shouldReceive('connection')->andReturn($mockConnection = m::mock('Illuminate\Database\ConnectionInterface'));
        $mockConnection->shouldreceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
        $mockConnection->shouldReceive('getQueryGrammar')->andReturn($queryGrammar = m::mock('Illuminate\Database\Query\Grammars\Grammar'));
        $queryGrammar->shouldReceive('getDateFormat')->andReturn('Y-m-d H:i:s');
        
        return $model;
    }
}