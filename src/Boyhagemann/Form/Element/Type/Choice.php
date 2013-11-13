<?php

namespace Boyhagemann\Form\Element\Type;

interface Choice
{
	public function choices($choices);
	public function getChoices();
}