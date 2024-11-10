<?php

namespace Hiraeth\Routing;

use Hiraeth;

/**
 * {@inheritDoc}
 */
class ResolvableProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			Resolvable::class
		];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @param Action $instance
	 */
	public function __invoke(object $instance, Hiraeth\Application $app): object
	{
		$instance->setResolver($app->get(Resolver::class));

		return $instance;
	}
}
