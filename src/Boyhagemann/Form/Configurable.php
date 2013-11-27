<?php

namespace Boyhagemann\Form;

/**
 * Interface Configurable
 *
 * @package Boyhagemann\Form
 */
interface Configurable
{
	public function configure(FormBuilder $fb);
}