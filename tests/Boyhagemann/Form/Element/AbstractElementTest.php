<?php

namespace Boyhagemann\Form\Element;

use Mockery as m;

class AbstractElementTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $element;

	public function setUp()
	{
		$this->element = m::mock('Boyhagemann\Form\Element\AbstractElement');
	}

	public function testSetName()
	{
		$this->element->shouldReceive('name')->once()->ordered();
//		$this->element->shouldReceive('size')->once()->ordered();

		$this->assertNull($this->element->name('test'));
	}

	public function teardown()
	{
		m::close();
	}
}