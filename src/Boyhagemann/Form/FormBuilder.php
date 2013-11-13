<?php

namespace Boyhagemann\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Boyhagemann\Form\Element;
use View, Form, Event, Session;

/**
 * Class FormBuilder2
 *
 * @package Boyhagemann\Form
 *
 * @method text Boyhagemann\Form\Element\Text
 */
class FormBuilder
{
	/**
	 * @var string
	 */
	protected $name;

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
		'role' => 'form',
		'class' => 'form-horizontal',
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
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * @var Eloquent
	 */
	protected $model;

	/**
	 * @var MessageBag
	 */
	protected $errors;

	/**
	 * @param FormElementContainer $container
	 */
	public function __construct(FormElementContainer $container)
	{
		$this->container = $container;
	}

	/**
	 * @param $view
	 * @return $this
	 */
	public function view($view)
	{
		$this->view = $view;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getElements()
	{
		return $this->elements;
	}

	/**
	 * @param $name
	 * @return Element
	 */
	public function get($name)
	{
		return $this->elements[$name];
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return Eloquent
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param array $defaults
	 * @return $this
	 */
	public function defaults(Array $defaults)
	{
		$this->defaults = $defaults;
		return $this;
	}

	/**
	 * @param string $route
	 * @param array  $params
	 * @return $this
	 */
	public function route($route, Array $params = array())
	{
		$this->options['route'] = array($route, $params);
		return $this;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function url($url)
	{
		$this->options['url'] = $url;
		return $this;
	}

	/**
	 * @param Model $model
	 * @return $this
	 */
	public function model(Model $model)
	{
		$this->model = $model;
		return $this;
	}

	/**
	 * @param string $method
	 * @return $this
	 */
	public function method($method)
	{
		$this->options['method'] = $method;
		return $this;
	}
	/**
	 * @return string
	 */
	public function build()
	{
		Event::fire('form.formBuilder.build.before', array($this));

		$this->setDefaults();
		$this->validate();

		if($this->view instanceof Closure) {
			return call_user_func_array($this->view, array($this));
		}

		$response = View::make($this->view, array('fb' => $this));

		Event::fire('form.formBuilder.build.after', array($response, $this));

		return $response;
	}

	/**
	 * @param Element $element
	 * @return string|View
	 */
	public function buildElement(Element $element)
	{
		Event::fire('form.formBuilder.buildElement.before', array($element, $this));

		$view = $element->getView();

		$state = '';
		$state .= $element->getValidationState() ? ' has-' . $element->getValidationState() : '';
		$state .= $element->isRequired() ? ' is-required' : '';

		$response = null;

		if($view instanceof Closure) {
			$response = call_user_func_array($view, array($element));
		}
		elseif($view) {
			$response = View::make($view, compact('element', 'state'));
		}

		Event::fire('form.formBuilder.buildElement.after', array($response, $element, $this));

		return $response;
	}

	/**
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
	 * @param Validator $validator
	 */
	protected function validate()
	{
		if(!$this->errors && Session::get('errors')) {
			$this->errors = Session::get('errors');
		}

		if(!$this->errors) {
			return $this;
		}

		foreach($this->getElements() as $name => $element) {
			$error = $this->errors->first($name);
			if($error) {
				$element->hasError()->help($error);
			}
		}
	}

	/**
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
	 * @param string $name
	 * @param string|Element $element
	 */
	public function register($name, $element)
	{
		$this->container->bind($name, $element);
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 */
	public function __call($alias, $arguments)
	{
		$element = $this->container->make($alias);
		$name = current($arguments);

		if(method_exists($element, 'name')) {
			$element->name($name);
		}

		$this->elements[$name] = $element;

		return $element;
	}
}