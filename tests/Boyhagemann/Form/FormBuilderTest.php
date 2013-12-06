<?php

namespace Boyhagemann\Form;

use Illuminate\Events\Dispatcher;
use Illuminate\Session\Store as Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Illuminate\Support\Facades\View;

class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $fb;

	public function setUp()
	{
		$session = new Session(new MockArraySessionStorage);

		$this->fb = new FormBuilder(new FormElementContainer, new Dispatcher, $session);
	}

	public function testGetFormElementContainer()
	{
		$this->assertInstanceOf('Boyhagemann\Form\FormElementContainer', $this->fb->getElementContainer());
	}

	public function testGetNonExistingOptionReturnsNull()
	{
		$this->assertNull($this->fb->getOption('test'));
	}

	public function testSetView()
	{
		$return = $this->fb->view('test');
		$this->assertSame($this->fb, $return);
	}

	public function testHasFormElementReturnsFalseIfElementDoesNotExist()
	{
		$this->assertFalse($this->fb->has('test'));
	}

	public function testHasFormElementReturnsTrueIfElementExists()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$this->assertTrue($this->fb->has('test'));
	}

	public function testGetFormElementReturnsElement()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$this->assertInstanceOf('Boyhagemann\Form\Element\Text', $this->fb->get('test'));
	}

	public function testAddingNonExistingElements()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->assertInstanceOf('Boyhagemann\Form\Element\Text', $this->fb->text('test'));
	}

	public function testRegisterElements()
	{
		$this->assertSame($this->fb, $this->fb->register('text', 'Boyhagemann\Form\Element\Text'));
	}

	public function testRemoveElementByName()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$return = $this->fb->remove('test');

		$this->assertSame($this->fb, $return);
		$this->assertFalse($this->fb->has('test'));
	}

	public function testRemoveElementByElement()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$text = $this->fb->text('test');
		$return = $this->fb->remove($text);

		$this->assertSame($this->fb, $return);
		$this->assertFalse($this->fb->has('test'));
	}

	public function testGetElements()
	{
		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$elements = $this->fb->getElements();

		$this->assertInternalType('array', $elements);
		$this->assertCount(1, $elements);
		$this->assertSame('test', key($elements)); // Name of the element
		$this->assertInstanceof('Boyhagemann\Form\Element\Text', current($elements));
	}

	public function testSetOption()
	{
		$this->assertSame($this->fb, $this->fb->option('foo', 'bar'));
	}

	/**
	 * @depends testSetOption
	 */
	public function testGetOption()
	{
		$this->fb->option('foo', 'bar');
		$this->assertSame('bar', $this->fb->getOption('foo'));
	}

	public function testSetName()
	{
		$this->assertSame($this->fb, $this->fb->name('test'));
	}

	/**
	 * @depends testSetName
	 * @depends testGetOption
	 */
	public function testGetName()
	{
		$this->fb->name('test');
		$this->assertSame('test', $this->fb->getName());
		$this->assertSame('test', $this->fb->getOption('name'));
	}

	public function testSetRoute()
	{
		$this->assertSame($this->fb, $this->fb->route('test'));
	}

	/**
	 * @depends testSetRoute
	 * @depends testGetOption
	 */
	public function testGetRoute()
	{
		$this->fb->route('test');
		$this->assertSame('test', $this->fb->getRoute());
		$this->assertSame('test', $this->fb->getOption('route'));
	}

	public function testSetUrl()
	{
		$this->assertSame($this->fb, $this->fb->url('test'));
	}

	/**
	 * @depends testSetUrl
	 * @depends testGetOption
	 */
	public function testGetUrl()
	{
		$this->fb->url('test');
		$this->assertSame('test', $this->fb->getUrl());
		$this->assertSame('test', $this->fb->getOption('url'));
	}

	public function testSetMethod()
	{
		$this->assertSame($this->fb, $this->fb->method('get'));
	}

	/**
	 * @depends testSetRoute
	 * @depends testGetOption
	 */
	public function testGetMethod()
	{
		$this->fb->method('get');
		$this->assertSame('GET', $this->fb->getMethod());
		$this->assertSame('GET', $this->fb->getAttribute('method'));
	}

	/**
	 * @depends testSetOption
	 */
	public function testGetOptions()
	{
		$this->fb->option('foo', 'bar');
		$options = $this->fb->getOptions();

		$this->assertInternalType('array', $options);
		$this->assertCount(1, $options);
		$this->assertSame('bar', current($options));
		$this->assertSame('foo', key($options));
	}

	public function testGetAttributes()
	{
		$attributes = $this->fb->getAttributes();

		$this->assertInternalType('array', $attributes);
		$this->assertCount(3, $attributes);
		$this->assertSame('method', key($attributes));
		$this->assertSame('POST', current($attributes));
	}

	public function testSetAttribute()
	{
		$this->assertSame($this->fb, $this->fb->attr('foo', 'bar'));
	}

	public function testGetAttribute()
	{
		$this->assertSame('POST', $this->fb->getAttribute('method'));
	}

	public function testSetModel()
	{
		$this->assertSame($this->fb, $this->fb->model(new \StdClass));
	}

	public function testGetModel()
	{
		$model = new \StdClass;
		$this->fb->model($model);

		$this->assertSame($model, $this->fb->getModel());
	}

	public function testSetDefaults()
	{
		$this->assertSame($this->fb, $this->fb->defaults(array('foo', 'bar')));
	}

//	public function testBuild()
//	{
//		View::shouldReceive('make')->once();
//
//		$form = $this->fb->build();
//
//		$this->assertInstanceof('Response', $form);
//	}
}