<?php

namespace Boyhagemann\Form\Element;

class Select extends AbstractElement implements Type\Choice
{
	protected $view = 'form::element.select';

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