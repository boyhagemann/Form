<?php

namespace Boyhagemann\Form\Element;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App, Closure;

abstract class ModelElement extends AbstractElement implements Type\Choice
{
	protected $model;
	protected $key = 'id';
	protected $field = 'title';
	protected $before;
	protected $after;
	protected $choices = array();
        protected $alias;

        /**
	 * @param string $field
	 * @return $this
	 */
	public function field($field)
	{
		$this->field = $field;
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function key($key)
	{
		$this->key = $key;
		return $this;
	}

	/**
	 * @param string|Eloquent $model
	 * @return $this
	 */
	public function model($model)
	{
		$this->model = $model;
		return $this;
	}

	/**
	 * @param string $alias
	 * @return $this
	 */
	public function alias($alias)
	{
		$this->alias = $alias;
		return $this;
	}

	/**
	 * @param array $choices
	 * @return $this
	 */
	public function choices($choices)
	{
		$this->choices = $choices;
		return $this;
	}

	/**
	 * @param callable $before
	 * @return $this
	 */
	public function before(Closure $before)
	{
		$this->before = $before;
		return $this;
	}


	/**
	 * @param callable $after
	 * @return $this
	 */
	public function after(Closure $after)
	{
		$this->after = $after;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getChoices()
	{
		if($this->choices) {
			return $this->choices;
		}

		if($this->model instanceof Eloquent) {
			$qb = $this->model->query();
		}
		else {
			$qb = App::make($this->model)->query();
		}

		if($this->before) {
			call_user_func_array($this->before, array($qb, $this));
		}

		$this->choices = $qb->lists($this->field, $this->key);

		if($this->after) {
			call_user_func_array($this->after, array($qb, $this));
		}

		return $this->choices;
	}
}