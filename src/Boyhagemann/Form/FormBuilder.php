<?php

namespace Boyhagemann\Form;

use Illuminate\Support\MessageBag;
use Boyhagemann\Form\Element;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Environment as Renderer;
use Illuminate\Events\Dispatcher;
use StdClass;

/**
 * Class FormBuilder
 *
 * @package Boyhagemann\Form
 */
class FormBuilder
{
	/**
	 * @var array
	 */
	protected $elements = array();

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @var array
	 */
	protected $attributes = array(
		'method' 	=> 'POST',
		'role' 		=> 'form',
		'class' 	=> 'form-horizontal',
	);

	/**
	 * @var string|Closure
	 */
	protected $view = 'form::form';

	/**
	 * @var FormElementContainer
	 */
	protected $container;

	/**
	 * @var Renderer
	 */
	protected $renderer;

	/**
	 * @var Dispatcher
	 */
	protected $events;

	/**
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * @var StdClass
	 */
	protected $model;

	/**
	 * @var MessageBag
	 */
	protected $errors;

	/**
	 * @param FormElementContainer $container
	 * @param Renderer             $renderer
	 * @param Dispatcher           $events
	 */
	public function __construct(FormElementContainer $container, Renderer $renderer, Dispatcher $events)
	{
		$this->container 	= $container;
		$this->renderer 	= $renderer;
		$this->events 		= $events;
	}

	/**
	 * Set the name for the form
	 *
	 * @param string $name
	 * @return $this
	 */
	public function name($name)
	{
		$this->options['name'] = $name;
		return $this;
	}

	/**
	 * Set the view path that is used for rendering the form
	 *
	 * @param string $view
	 * @return $this
	 */
	public function view($view)
	{
		$this->view = $view;
		return $this;
	}

	/**
	 * Get a specific form element by name
	 *
	 * @param string $name
	 * @return Element
	 */
	public function get($name)
	{
		if(!$this->has($name)) {
			return;
		}

		return $this->elements[$name];
	}

	/**
	 * Check if the form has a specific element
	 *
	 * @param string $name
	 * @return Element
	 */
	public function has($name)
	{
		return isset($this->elements[$name]);
	}

	/**
	 * Remove an element from the form
	 *
	 * @param string|Element $element
	 * @return $this
	 */
	public function remove($element)
	{
		if($element instanceof Element) {
			$element = $element->getName();
		}

		unset($this->elements[$element]);
		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return $this
	 */
	public function option($name, $value)
	{
		$this->options[$name] = $value;
		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return $this
	 */
	public function attr($name, $value)
	{
		$this->attributes[$name] = $value;
		return $this;
	}

	/**
	 * Get all the form elements
	 *
	 * @return array
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 * Get the IoC container that resolves all the form elements
	 *
	 * @return FormElementContainer
	 */
	public function getElementContainer()
	{
		return $this->container;
	}

	/**
	 * Get the name of the form. This name is used as the
	 * form name attribute
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getOption('name');
	}

	/**
	 * Get the route that will be used in the form action attribute
	 *
	 * @return string
	 */
	public function getRoute()
	{
		return $this->getOption('route');
	}

	/**
	 * Get the url that will be used in the form action attribute
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->getOption('url');
	}

	/**
	 * Get the current form method
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->getAttribute('method');
	}

	/**
	 * Get all the form options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Get a specific form option
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getOption($name)
	{
		if(!isset($this->options[$name])) {
			return;
		}

		return $this->options[$name];
	}

	/**
	 * Get all the form attributes
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Get a specific form attribute
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name)
	{
		if(!isset($this->attributes[$name])) {
			return;
		}

		return $this->attributes[$name];
	}

	/**
	 * Get the model object
	 *
	 * @return StdClass
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Set the defaults for each element
	 *
	 * @param array $defaults
	 * @return $this
	 */
	public function defaults(Array $defaults)
	{
		$this->defaults = $defaults;
		return $this;
	}

	/**
	 * Add a route to use as the form action attribute
	 *
     * @param string $route
     * @param mixed  $params
     * @return $this
     */
    public function route($route, $params = array())
    {
        if ($params) {
            $this->options['route'] = array($route, $params);
        }
        else {
            $this->options['route'] = $route;
        }

        return $this;
    }

    /**
	 * Add a url to the form action attribute
	 *
	 * @param string $url
	 * @return $this
	 */
	public function url($url)
	{
		$this->options['url'] = $url;
		return $this;
	}

	/**
	 * Attach a model to the form. This will keep the form values and the
	 * model in sync.
	 *
	 * @param StdClass $model
	 * @return $this
	 */
	public function model(StdClass $model)
	{
		$this->model = $model;
		return $this;
	}

	/**
	 * Add the form method
	 *
	 * @param string $method
	 * @return $this
	 */
	public function method($method)
	{
		$this->attributes['method'] = strtoupper($method);
		return $this;
	}

	/**
	 * @return string
	 */
	public function build()
	{
		$this->events->fire('form.formBuilder.build.before', array($this));

		$this->setDefaults();
		$this->validate();

		if($this->view instanceof Closure) {
			return call_user_func_array($this->view, array($this));
		}

		$response = $this->renderer->make($this->view, array('fb' => $this));

		$this->events->fire('form.formBuilder.build.after', array($response, $this));

		return $response;
	}

	/**
	 * @param Element $element
	 * @return string|View
	 */
	public function buildElement(Element $element)
	{
		$this->events->fire('form.formBuilder.buildElement.before', array($element, $this));

		$response = $element->getView();

		$state = '';
		$state .= $element->getValidationState() ? ' has-' . $element->getValidationState() : '';
		$state .= $element->isRequired() ? ' is-required' : '';

		if($response instanceof Closure) {
			$response = call_user_func_array($response, array($element));
		}
		elseif($this->renderer->exists($response)) {
			$response = $this->renderer->make($response, compact('element', 'state'));
		}

		$this->events->fire('form.formBuilder.buildElement.after', array($response, $element, $this));

		return $response;
	}

	/**
	 * Set a message bag of previously fetched errors, so the elements
	 * will have a correct error help text for instance.
	 *
	 * @param MessageBag $errors
	 * @return $this
	 */
	public function errors(MessageBag $errors = null)
	{
		if($errors) {
			$this->errors = $errors;
		}

		return $this;
	}

	/**
	 * Test if the form values are valid. It will validate each
	 * element and add an error to that element if the validation
	 * fails.
	 *
	 * @param Validator $validator
	 */
	protected function validate()
	{
		// Are there any errors in the session? And are there no errors
		// set yet? Then use the errors in the session.
//		if(!$this->errors && Session::get('errors')) {
//			$this->errors = Session::get('errors');
//		}

		// If there are no errors, then we don't have to do
		// anything more.
		if(!$this->errors) {
			return $this;
		}

		// Add the errors to the elements
		foreach($this->getElements() as $name => $element) {

			// For now, we only use the first error
			$error = $this->errors->first($name);
			if($error) {
				$element->hasError()->help($error);
			}
		}
	}

	/**
	 * After all elements are added to the form, we can set the
	 * default values we collected earlier.
	 *
	 */
	protected function setDefaults()
	{
		// Set the default values
		foreach($this->defaults as $name => $element) {
			if(isset($this->elements[$name])) {
				$this->get($name)->value($element);
			}
		}
	}

	/**
	 * Get all the rules from all the elements
	 *
	 * @return array
	 */
	public function getRules()
	{
		$rules = array();
		foreach($this->getElements() as $name => $element) {
			if($element->getRules()) {
				$rules[$name] = $element->getRules();
			}
		}

		return $rules;
	}

	/**
	 * Register a new Boyhagemann\Form\Element interface to the element
	 * container.
	 *
	 * If the element already exists, then this element will not be registered.
	 *
	 * @param string $name
	 * @param string|Element $element
	 * @return $this
	 */
	public function register($name, $element)
	{
		$this->container->bindIf($name, $element);
		return $this;
	}

	/**
	 * Add an element to the current instance of the FormBuilder.
	 *
	 * @param string $alias
	 * @param string $name
	 * @param string $class
	 * @return Element
	 */
	public function element($alias, $name, $class = null)
	{
		// If the element is not registered yet, register it now
		if($class) {
			$this->register($alias, $class);
		}

		// Get the element instance from the container
		$element = $this->container->make($alias);
		$element->name($name);

		// Add the element to the current FormBuilder instance
		$this->elements[$name] = $element;

		return $element;
	}

	/**
	 * Try to match a method call to a registered element.
	 *
	 * An example would be:
	 *
	 *   $fb->text('title');
	 *
	 * This call will look for an element with the alias 'text'
	 * and once found, it will add it to the current instance
	 * of the FormBuilder.
	 *
	 * @param $alias
	 * @param $arguments
	 * @throws \ReflectionException
	 * @return mixed
	 */
	public function __call($alias, $arguments)
	{
		// Check if the element is registered to the container
		$this->container->make($alias);

		$name = current($arguments);
		return $this->element($alias, $name);
	}
}