<?php

namespace Hiraeth\Routing;

use Hiraeth;

/**
 * Delegates are responsible for constructing dependencies for the dependency injector.
 */
class ResolverDelegate implements Hiraeth\Delegate
{
	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Resolver::class;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$resolver = new Resolver($app->get(Hiraeth\Broker::class));

		$resolver->setAdapters(array_merge(...array_values(
			$app->getConfig('*', 'routing.adapters', [])
		)));

		$resolver->setResponders(array_merge(...array_values(
			$app->getConfig('*', 'routing.responders', [])
		)));

		return $app->share($resolver);
	}
}
