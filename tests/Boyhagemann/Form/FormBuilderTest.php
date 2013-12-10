<?php

namespace Boyhagemann\Form;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Mockery;

class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormBuilder
	 */
	protected $fb;

	public function setUp()
	{
		$container = new FormElementContainer;

		$events = new \Illuminate\Events\Dispatcher;
		$filesystem = new \Illuminate\Filesystem\Filesystem;
		$finder = new \Illuminate\View\FileViewFinder($filesystem, array());
		$finder->addNamespace('form', array(
			'form' => getcwd() . '/src/views'
		));

		$resolver = new \Illuminate\View\Engines\EngineResolver;
		$resolver->register('blade', function() use($filesystem) {
			$compiler = new \Illuminate\View\Compilers\BladeCompiler($filesystem, null);
			return new \Illuminate\View\Engines\CompilerEngine($compiler, $filesystem);
		});

		$renderer = new \Illuminate\View\Environment($resolver, $finder, $events);

		$this->fb = new FormBuilder($container, $renderer, $events);
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
		$this->assertNull($this->fb->get('non-existing'));
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
		$this->fb->nonExistingElement('test');
	}

	public function testBuild()
	{
		$form = $this->fb->build();
		$this->assertInstanceof('Illuminate\View\View', $form);
		$this->assertArrayHasKey('fb', $form->getData());
		$this->assertInstanceof('Boyhagemann\Form\FormBuilder', $form->offsetGet('fb'));
	}

	public function testBuildElement()
	{
		$element = $this->fb->text('test');

		$response = $this->fb->buildElement($element);
		$this->assertInstanceof('Illuminate\View\View', $response);
		$this->assertArrayHasKey('element', $response->getData());
		$this->assertInstanceof('Boyhagemann\Form\Element\Text', $response->offsetGet('element'));
	}

	public function testSetDefaultsAfterBuildAddsDefaultValueToElement()
	{
		$this->fb->text('foo');
		$this->fb->defaults(array('foo' => 'bar'));
		$this->fb->build();

		$this->assertSame('bar', $this->fb->get('foo')->getValue());
	}

	public function testGetRules()
	{
		$this->fb->text('foo')->rules('bar');
		$rules = $this->fb->getRules();

		$this->assertSame(array('foo' => 'bar'), $rules);
	}

	public function testValidateSetErrorInElementIfThereAreErrorsAfterBuild()
	{
		$errors = new \Illuminate\Support\MessageBag(array('foo' => 'bar'));

		$this->fb->text('foo');
		$this->fb->errors($errors);
		$this->fb->build();

		$this->assertSame('error', $this->fb->get('foo')->getValidationState());
		$this->assertSame('bar', $this->fb->get('foo')->getHelp());
	}

	public function testViewCanBeClosure()
	{
		$this->fb->view(function() {
			return 'test';
		});

		$this->assertSame('test', $this->fb->build());
	}
}