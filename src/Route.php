<?php

namespace Hiraeth\Routing;

/**
 * A route is a simple container for a resolvable target (via adapters) and its parameters
 */
class Route
{
	/**
	 * @var string[]
	 */
	protected $parameters = array();


	/**
	 * @var mixed
	 */
	protected $target = NULL;


	/**
	 * @param mixed $target The target for this route (usually a class or callback string)
	 * @param string[] $parameters The parameters for the route
	 */
	public function __construct($target, array $parameters = array())
	{
		$this->target     = $target;
		$this->parameters = $parameters;
	}


	/**
	 * @return string[]
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}


	/**
	 * @return mixed
	 */
	public function getTarget()
	{
		return $this->target;
	}
}
