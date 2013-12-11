<?php

namespace Boyhagemann\Form;

use Mockery as m;

class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $fb;

	protected $container;
	protected $view;
	protected $events;

	public function setUp()
	{
		$this->container = m::mock('Boyhagemann\Form\FormElementContainer');
		$this->view = m::mock('Illuminate\View\Environment');
		$this->events = m::mock('Illuminate\Events\Dispatcher');

		$this->fb = new FormBuilder($this->container, $this->view , $this->events);
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
		$this->mockTextElement();

		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$this->assertTrue($this->fb->has('test'));
	}

	public function testGetFormElementReturnsElement()
	{
		$this->mockTextElement();

		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$this->assertInstanceOf('Boyhagemann\Form\Element\Text', $this->fb->get('test'));
		$this->assertNull($this->fb->get('non-existing'));
	}

	public function testAddingNonExistingElements()
	{
		$this->mockTextElement();

		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->assertInstanceOf('Boyhagemann\Form\Element\Text', $this->fb->text('test'));
	}

	public function testRemoveElementByName()
	{
		$this->mockTextElement();

		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$this->fb->text('test');
		$return = $this->fb->remove('test');

		$this->assertSame($this->fb, $return);
		$this->assertFalse($this->fb->has('test'));
	}

	public function testRemoveElementByElement()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('getName')->andReturn('test');

		$this->fb->register('text', 'Boyhagemann\Form\Element\Text');
		$text = $this->fb->text('test');
		$return = $this->fb->remove($text);

		$this->assertSame($this->fb, $return);
		$this->assertFalse($this->fb->has('test'));
	}

	public function testGetElements()
	{
		$this->mockTextElement();

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
		$this->assertNull($this->fb->getOption('non-existing'));
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
		$this->assertSame($this->fb, $this->fb->route('test', array('foo' => 'bar')));
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
		$this->assertNull($this->fb->getAttribute('non-existing'));
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

	/**
	 * @expectedException ReflectionException
	 */
	public function testCallingNonExistingElementThrowsException()
	{
		$this->container->shouldReceive('make')->andThrow('ReflectionException');

		$this->fb->nonExistingElement('test');
	}

	public function testBuild()
	{
		$view = m::mock('Illuminate\View\View');
		$view->shouldReceive('getData')->once()->andReturn(array('fb' => $this->fb));
		$view->shouldReceive('offsetGet')->with('fb')->once()->andReturn($this->fb);

		$this->events->shouldReceive('fire')->twice();
		$this->view->shouldReceive('make')->once()->andReturn($view);

		$form = $this->fb->build();
		$this->assertInstanceof('Illuminate\View\View', $form);
		$this->assertArrayHasKey('fb', $form->getData());
		$this->assertInstanceof('Boyhagemann\Form\FormBuilder', $form->offsetGet('fb'));
	}

	public function testBuildElement()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('getView')->andReturn('test');
		$element->shouldReceive('getView')->andReturn('test');
		$element->shouldReceive('getValidationState')->andReturn('');

		$response = m::mock('Illuminate\View\View');
		$response->shouldReceive('getData')->andReturn(array('element' => $element));
		$response->shouldReceive('offsetGet')->with('element')->once()->andReturn($element);

		$this->events->shouldReceive('fire')->twice();
		$this->view->shouldReceive('exists')->once()->andReturn(true);
		$this->view->shouldReceive('make')->once()->andReturn($response);

		$element = $this->fb->text('test');

		$response = $this->fb->buildElement($element);
		$this->assertInstanceof('Illuminate\View\View', $response);
		$this->assertArrayHasKey('element', $response->getData());
		$this->assertInstanceof('Boyhagemann\Form\Element\Text', $response->offsetGet('element'));
	}

	public function testBuildElementDoesReturnsIfElementIsNotOfPresentableInterface()
	{
		$element = new \StdClass();

		$this->assertNull($this->fb->buildElement($element));
	}

	public function testBuildElementCanRenderClosure()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('getValidationState')->andReturn('');
		$element->shouldReceive('getView')->once()->andReturn(function() {
			return 'test';
		});

		$this->events->shouldReceive('fire')->twice();

		$this->assertSame('test', $this->fb->buildElement($element));
	}

	public function testBuildElementRendersNothingIfViewDoesNotExist()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('getValidationState')->andReturn('');
		$element->shouldReceive('getView')->andReturn('non-existing');

		$this->events->shouldReceive('fire')->twice();
		$this->view->shouldReceive('exists')->once()->andReturn(true);
		$this->view->shouldReceive('make')->once()->andReturn('');

		$this->assertSame('', $this->fb->buildElement($element));
	}

	public function testSetDefaultsAfterBuildAddsDefaultValueToElement()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('getValue')->once()->andReturn('bar');

		$this->events->shouldReceive('fire')->twice();
		$this->view->shouldReceive('make')->once();

		$this->fb->text('test');
		$this->fb->defaults(array('foo' => 'bar'));
		$this->fb->build();

		$this->assertSame('bar', $this->fb->get('test')->getValue());
	}

	public function testGetRules()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('rules')->once()->with('bar')->andReturn($element);
		$element->shouldReceive('getRules')->twice()->andReturn('bar');

		$this->fb->text('test')->rules('bar');
		$rules = $this->fb->getRules();

		$this->assertSame(array('test' => 'bar'), $rules);
	}

	public function testValidateSetErrorInElementIfThereAreErrorsAfterBuild()
	{
		$element = $this->mockTextElement();
		$element->shouldReceive('withError')->once()->andReturn($element);
		$element->shouldReceive('getValidationState')->andReturn('error');
		$element->shouldReceive('help')->once()->andReturn($element);
		$element->shouldReceive('getHelp')->once()->andReturn('bar');

		$errors = m::mock('Illuminate\Support\MessageBag');
		$errors->shouldReceive('first')->once()->andReturn(array('test' => 'bar2'));

		$this->events->shouldReceive('fire')->twice();
		$this->view->shouldReceive('make')->once();

		$this->fb->text('test');
		$this->fb->errors($errors);
		$this->fb->build();

		$this->assertSame('error', $this->fb->get('test')->getValidationState());
		$this->assertSame('bar', $this->fb->get('test')->getHelp());
	}

	public function testViewCanBeClosure()
	{
		$this->events->shouldReceive('fire')->twice();

		$this->fb->view(function() {
			return 'test';
		});

		$this->assertSame('test', $this->fb->build());
	}

	public function mockTextElement()
	{
		$element = m::mock('Boyhagemann\Form\Element\Text');
		$element->shouldReceive('name')->with('test')->andReturn($element);
		$this->container->shouldReceive('bindIf');
		$this->container->shouldReceive('make')->andReturn($element);
		$element->shouldReceive('isRequired')->andReturn(false);

		return $element;
	}

	public function tearDown()
	{
		m::close();
	}
}