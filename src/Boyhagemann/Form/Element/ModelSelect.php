<?php

namespace Boyhagemann\Form\Element;

class ModelSelect extends ModelElement
{
	protected $view = 'form::element.select';
	protected $blank;

	/**
	 * @param $blank
	 * @return $this
	 */
	public function blank($blank)
	{
		$this->blank = $blank;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getChoices()
	{
		if(!$this->blank) {
			return parent::getChoices();
		}

		return array_merge(array('' => $this->blank), parent::getChoices());
	}
}