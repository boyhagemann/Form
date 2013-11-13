<?php

namespace Boyhagemann\Form\Element;

class Textarea extends AbstractElement implements Type\Textarea
{
	protected $view = 'form::element.textarea';

	/**
	 * @param $cols
	 * @return $this
	 */
	public function cols($cols)
	{
		return $this->attr('cols', $cols);
	}

	/**
	 * @param $rows
	 * @return $this
	 */
	public function rows($rows)
	{
		return $this->attr('rows', $rows);
	}

	/**
	 * @return integer
	 */
	public function getCols()
	{
		return $this->getAttribute('cols');
	}

	/**
	 * @return integer
	 */
	public function getRows()
	{
		return $this->getAttribute('rows');
	}
}