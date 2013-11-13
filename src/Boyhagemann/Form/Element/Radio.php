<?php

namespace Boyhagemann\Form\Element;

class Radio extends AbstractElement implements Type\Choice
{
	protected $view = 'form::element.radio';
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