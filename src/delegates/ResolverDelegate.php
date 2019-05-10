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
		$resolver   = new Resolver($app);
		$adapters   = $app->getConfig('*', 'routing.adapters', []);
		$responders = $app->getConfig('*', 'responder', []);

		usort($responders, function($a, $b) {
			$a_priority = $a['priority'] ?? 50;
			$b_priority = $b['priority'] ?? 50;

			return $a_priority - $b_priority;
		});

		$resolver->setAdapters(array_merge(...array_values($adapters)));

		$resolver->setResponders(array_filter(array_map(function($config) {
			if ($config['disabled'] ?? FALSE) {
				return NULL;
			}

			if (empty($config['class'])) {
				return NULL;
			}

			return $config['class'];
		}, $responders)));


		return $app->share($resolver);
	}
}
