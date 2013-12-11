<?php

namespace Boyhagemann\Form\Element\Type;

interface Choice
{
	public function choices(Array $choices);
	public function getChoices();
}