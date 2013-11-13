<?php

namespace Boyhagemann\Form\Element;

class Checkbox extends AbstractElement implements Type\MultipleChoice
{
	protected $view = 'form::element.checkboxes';
	protected $value = array();
	protected $attributes = array();

	/**
	 * @param $choices
	 * @return $this
	 */
	public function choices($choices)
	{
		$this->choices = $choices;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getChoices()
	{
		return $this->choices;
	}
}