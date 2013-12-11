<?php

namespace Boyhagemann\Form\Element;

class AbstractElementTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $element;

	public function setUp()
	{
		$this->element = new TestElement;
	}

	public function testSetName()
	{
		$this->assertSame($this->element, $this->element->name('test'));
	}

	public function testGetName()
	{
		$this->element->name('test');
		$this->assertSame('test', $this->element->getName());
	}

	public function testSetLabel()
	{
		$this->assertSame($this->element, $this->element->label('test'));
	}

	public function testGetLabel()
	{
		$this->element->label('test');
		$this->assertSame('test', $this->element->getLabel());
	}

	public function testSetValue()
	{
		$this->assertSame($this->element, $this->element->value('test'));
	}

	public function testGetValue()
	{
		$this->element->value('test');
		$this->assertSame('test', $this->element->getValue());
	}

	public function testSetMap()
	{
		$this->assertSame($this->element, $this->element->map());
	}

	public function testIsMapped()
	{
		$this->element->map();
		$this->assertSame(true, $this->element->isMapped());

		$this->element->map(false);
		$this->assertSame(false, $this->element->isMapped());
	}

	public function testSetRequired()
	{
		$this->assertSame($this->element, $this->element->required());
		$this->assertSame($this->element, $this->element->required(true));
		$this->assertSame($this->element, $this->element->required(false));
	}

	public function testIsRequired()
	{
		$this->assertSame(false, $this->element->isRequired());
		$this->assertSame('', $this->element->getRules());

		$this->element->required(true);
		$this->assertSame(true, $this->element->isRequired());
		$this->assertSame('required', $this->element->getRules());

		$this->element->required(false);
		$this->assertSame(false, $this->element->isRequired());
		$this->assertSame('', $this->element->getRules());
	}


	public function testSetDisabled()
	{
		$this->assertSame($this->element, $this->element->disabled());
		$this->assertSame($this->element, $this->element->disabled(true));
		$this->assertSame($this->element, $this->element->disabled(false));
	}

	public function testIsDisabled()
	{
		$this->element->disabled();
		$this->assertSame(true, $this->element->isDisabled());

		$this->element->disabled(false);
		$this->assertSame(false, $this->element->isDisabled());

		$this->element->disabled(true);
		$this->assertSame(true, $this->element->isDisabled());
	}

	public function testSetHelp()
	{
		$this->assertSame($this->element, $this->element->help('test'));
	}

	public function testGetHelp()
	{
		$this->element->help('test');
		$this->assertSame('test', $this->element->getHelp());
	}

	public function testSetAttriibute()
	{
		$this->assertSame($this->element, $this->element->attr('foo', 'bar'));
	}

	public function testGetAttribute()
	{
		$this->element->attr('foo', 'bar');
		$this->assertSame('bar', $this->element->getAttribute('foo'));
	}

	public function testGetAttributes()
	{
		$expected = array(
			'class' => 'form-control',
		);

		$this->assertSame($expected, $this->element->getAttributes());
	}

	public function testSetOption()
	{
		$this->assertSame($this->element, $this->element->option('foo', 'bar'));
	}

	public function testGetOption()
	{
		$this->element->option('foo', 'bar');
		$this->assertSame('bar', $this->element->getOption('foo'));
	}

	public function testGetOptions()
	{
		$this->assertSame(array(), $this->element->getOptions());
	}

	public function testSetView()
	{
		$this->assertSame($this->element, $this->element->view('test'));
	}

	public function testGetView()
	{
		$this->element->view('test');
		$this->assertSame('test', $this->element->getView());
	}

	public function testWith()
	{
		$this->assertSame($this->element, $this->element->withSuccess());
		$this->assertSame($this->element, $this->element->withWarning());
		$this->assertSame($this->element, $this->element->withError());
	}

	public function testGetValidationState()
	{
		$this->assertSame(AbstractElement::STATE_SUCCESS,	$this->element->withSuccess()->getValidationState());
		$this->assertSame(AbstractElement::STATE_WARNING, 	$this->element->withWarning()->getValidationState());
		$this->assertSame(AbstractElement::STATE_ERROR, 	$this->element->withError()->getValidationState());
	}

	public function testAddRule()
	{
		$this->assertSame($this->element, $this->element->rule('test'));
	}

	public function testGetRules()
	{
		$this->assertSame('', $this->element->getRules());

		$this->element->rule('max:10');
		$this->assertSame('max:10', $this->element->getRules());

		$this->element->required();
		$this->assertSame('required|max:10', $this->element->getRules());

		$this->element->rule('required');
		$this->assertSame('required|max:10', $this->element->getRules());
	}

	public function testGetRulesAsArray()
	{
		$this->assertSame(array(), $this->element->getRulesAsArray());
	}

	public function testRemoveRule()
	{
		$this->element->required()->removeRule('required');
		$this->assertSame('', $this->element->getRules());
	}

}

class TestElement extends AbstractElement {}