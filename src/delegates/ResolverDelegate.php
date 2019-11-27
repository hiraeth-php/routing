<?php

namespace Hiraeth\Routing;

use Hiraeth;

/**
 * {@inheritDoc}
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
	 * Sort configs based on priority
	 */
	protected function sort($a, $b)
	{
		return $a['priority'] - $b['priority'];
	}


	/**
	 * Load the class for the config
	 */
	protected function load($config)
	{
		return !$config['disabled']
			? $config['class']
			: NULL;
	}
}
