<?php

namespace Boyhagemann\Form\Contract;

interface PresentableElement
{
	public function help($help);
	public function view($view);

	public function getHelp();
	public function getView();

	public function map($map);
	public function isMapped();

	public function rules($rules);
	public function getRules();
	public function hasRule($rule);
	public function removeRule($rule);

	public function withSuccess();
	public function withError();
	public function withWarning();
	public function getValidationState();

}