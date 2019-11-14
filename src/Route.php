<?php

namespace Hiraeth\Routing;

/**
 *
 */
class Route
{
	/**
	 *
	 */
	protected $parameters = array();


	/**
	 *
	 */
	protected $target = NULL;


	/**
	 *
	 */
	public function __construct($target, array $parameters = array())
	{
		$this->target     = $target;
		$this->parameters = $parameters;
	}


	/**
	 *
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}


	/**
	 *
	 */
	public function getTarget()
	{
		return $this->target;
	}
}
