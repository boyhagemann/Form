<?php

namespace Boyhagemann\Form\Element;

class Radio extends AbstractElement implements Type\Choice
{
	protected $choices = array();
	protected $view = 'form::element.radio';
	protected $attributes = array();

	/**
	 * @param $choices
	 * @return $this
	 */
	public function choices(Array $choices)
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