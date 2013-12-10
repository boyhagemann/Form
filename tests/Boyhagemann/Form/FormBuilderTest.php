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
		$this->fb = new FormBuilder(new FormElementContainer);
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
//		$app = new \Illuminate\Container\Container;
//		$app->bind('events', 'Illuminate\Events\Dispatcher');
//		$app['config'] = array(
//			'session.driver' => 'array',
//			'view.paths' => array(),
//		);
//		$app['session'] = $app->share(function($app)
//		{
//			return new \Illuminate\Session\SessionManager($app);
//		});
//
//		$provider = new \Illuminate\View\ViewServiceProvider($app);
//		$provider->registerEngineResolver();
//		$provider->registerViewFinder();
//		$provider->registerEnvironment();
//
//		$files = new \Illuminate\Filesystem\FilesystemServiceProvider($app);
//		$files->register();
//
//
//
//		View::setFacadeApplication($app);
//		View::shouldReceive('make')->once()->andReturn('test22');
//
//
////		Event::shouldReceive('fire')->once();
//
//
//		$form = $this->fb->build();

	}
}