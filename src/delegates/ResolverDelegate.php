<?php

namespace Hiraeth\Routing;

use Hiraeth;

/**
 * Delegates are responsible for constructing dependencies for the dependency injector.
 */
class ResolverDelegate implements Hiraeth\Delegate
{
	/**
	 *
	 */
	protected $app = NULL;


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
	 *
	 */
	public function __construct(Hiraeth\Application $app)
	{
		$this->app = $app;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Broker $broker The dependency injector instance
	 * @return object The instance of the class for which the delegate operates
	 */
	public function __invoke(Hiraeth\Broker $broker): object
	{
		$resolver = new Resolver($broker);

		$resolver->setAdapters(array_merge(...array_values(
			$this->app->getConfig('*', 'routing.adapters', [])
		)));

		$resolver->setResponders(array_merge(...array_values(
			$this->app->getConfig('*', 'routing.responders', [])
		)));

		$broker->share($resolver);

		return $resolver;
	}
}
