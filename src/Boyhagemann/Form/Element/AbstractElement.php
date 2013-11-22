<?php

namespace Boyhagemann\Form\Element;

use Boyhagemann\Form\Element;
use Boyhagemann\Form\View;

abstract class AbstractElement implements Element
{
	protected $name;
	protected $label;
	protected $value;
	protected $disabled = false;
	protected $help;
	protected $rules;
	protected $view;
	protected $map = true;
	protected $validationState;
	protected $options = array();
	protected $attributes = array(
		'class' => 'form-control',
	);

	/**
	 * @param $name
	 * @return $this
	 */
	public function name($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param $label
	 * @return $this
	 */
	public function label($label)
	{
		$this->label = $label;
		return $this;
	}

	/**
	 * @param $value
	 * @return $this
	 */
	public function value($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @param bool $map
	 * @return $this
	 */
	public function map($map = true)
	{
		$this->map = (bool) $map;
		return $this;
	}

	/**
	 * @param $required
	 * @return $this
	 */
	public function required($required = true)
	{
		if($required) {
			$this->rule('required');
		}
		return $this;
	}
	/**
	 * @param $disabled
	 * @return $this
	 */
	public function disabled($disabled = true)
	{
		$this->disabled = (bool) $disabled;
		return $this;
	}

	/**
	 * @param $help
	 * @return $this
	 */
	public function help($help)
	{
		$this->help = $help;
		return $this;
	}

	/**
	 * @param $attr
	 * @param $value
	 * @return $this
	 */
	public function attr($attr, $value)
	{
		$this->attributes[$attr] = $value;
		return $this;
	}

	/**
	 * @param $option
	 * @param $value
	 * @return $this
	 */
	public function option($option, $value)
	{
		$this->options[$option] = $value;
		return $this;
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
	 * @param string|Array $rules
	 * @return $this
	 */
	public function rules($rules)
	{
		if(is_array($rules)) {
			foreach($rules as $rule) {
				$this->rule($rule);
			}
		}
		else {
			$this->rules = $rules;
		}

		return $this;
	}

	/**
	 * @param string $rule
	 * @return $this
	 */
	public function rule($rule)
	{
		if($this->rules) {
			$this->rules = $rule . '|' . $this->rules;
		}
		else {
			$this->rules = $rule;
		}

		return $this;
	}

	/**
	 * @param string $rule
	 * @return bool
	 */
	public function hasRule($rule)
	{
		return in_array($rule, $this->getRulesAsArray());
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getHelp()
	{
		return $this->help;
	}

	/**
	 * @return string
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * @return array
	 */
	public function getRulesAsArray()
	{
		return explode('|', $this->getRules());
	}

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		return in_array('required', $this->getRulesAsArray());
	}

	/**
	 * @return bool
	 */
	public function isMapped()
	{
		return $this->map;
	}


	/**
	 * @return bool
	 */
	public function isDisabled()
	{
		return $this->disabled;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @return string|Closure
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @return $this
	 */
	public function hasSuccess()
	{
		$this->validationState = 'success';
		return $this;
	}

	/**
	 * @return $this
	 */
	public function hasError()
	{
		$this->validationState = 'error';
		return $this;
	}

	/**
	 * @return $this
	 */
	public function hasWarning()
	{
		$this->validationState = 'warning';
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getValidationState()
	{
		return $this->validationState;
	}
}