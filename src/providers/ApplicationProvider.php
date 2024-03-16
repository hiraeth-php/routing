<?php

namespace Hiraeth\Routing;

use Hiraeth;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * {@inheritDoc}
 */
class ApplicationProvider implements Hiraeth\Provider
{
	/**
	 * {@inheritDoc}
	 */
	static public function getInterfaces(): array
	{
		return [
			Hiraeth\Application::class
		];
	}


	/**
	 * {@inheritDoc}
	 *
	 * @param Hiraeth\Application $instance The application instance
	 */
	public function __invoke($instance, Hiraeth\Application $app): object
	{
		if (!$app->has(RequestHandler::class)) {

			//
			// If we don not have a registered request handler yet, register our handler.
			//

			$instance->get(Hiraeth\Broker::class)->alias(RequestHandler::class, Handler::class);
		}

		return $instance;
	}
}
