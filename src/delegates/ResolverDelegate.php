<?php

namespace Hiraeth\Routing;

use Hiraeth;

/**
 * Delegates are responsible for constructing dependencies for the dependency injector.
 */
class ResolverDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Resolver::class;
	}


	/**
	* {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$resolver   = new Resolver($app);
		$defaults   = [
			'class'    => NULL,
			'disabled' => FALSE,
			'priority' => 50
		];

		$adapters   = $app->getConfig('*', 'adapter', $defaults);
		$responders = $app->getConfig('*', 'responder', $defaults);

		usort($adapters, [$this, 'sort']);
		usort($responders, [$this, 'sort']);

		$resolver->setAdapters(array_filter(array_map([$this, 'load'], $adapters)));
		$resolver->setResponders(array_filter(array_map([$this, 'load'], $responders)));


		return $app->share($resolver);
	}


	/**
	 *
	 */
	protected function sort($a, $b)
	{
		return $a_priority - $b_priority;
	}


	/**
	 *
	 */
	protected function load($config)
	{
		if ($config['disabled']) {
			return NULL;
		}

		return $config['class'];
	}
}
