<?php

namespace Boyhagemann\Form\Contract;

interface HtmlElement
{
	public function name($name);
	public function label($label);
	public function value($value);
	public function required($required = true);
	public function attr($attr, $value);
	public function option($option, $value);
	public function disabled($disable = true);

	public function getName();
	public function getLabel();
	public function getValue();
	public function getOptions();
	public function getAttributes();
	public function isRequired();
	public function isDisabled();
}