<?php

namespace Hiraeth\Routing;

use Hiraeth\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;

/**
 *
 */
class Resolver implements ResolverInterface
{
	/**
	 *
	 */
	protected $adapters = array();


	/**
	 *
	 */
	protected $app = NULL;


	/**
	 *
	 */
	protected $request = NULL;


	/**
	 *
	 */
	protected $responders = array();


	/**
	 *
	 */
	protected $response = NULL;


	/**
	 *
	 */
	protected $target = NULL;


	/**
	 *
	 */
	protected $result = NULL;


	/**
	 *
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}


	/**
	 *
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}


	/**
	 *
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}


	/**
	 *
	 */
	public function getResult()
	{
		return $this->result;
	}


	/**
	 *
	 */
	public function getTarget()
	{
		return $this->target;
	}


	/**
	 * Resolve a target returned by `RouterInterface::match()` to a PSR-7 response
	 *
	 * @access public
	 * @param Route $route The route to run
	 * @param Request $request The server request that matched the target
	 * @param Response $response The response object to modify for return
	 * @return Response The PSR-7 response from running the target
	 */
	public function run(Route $route, Request $request, Response $response): Response
	{
		$this->target     = $route->getTarget();
		$this->request    = $request;
		$this->response   = $response;
		$parameters       = array();

		foreach ($route->getParameters() as $parameter => $value) {
			$parameters[':' . $parameter] = $value;
		}

		foreach ($this->adapters as $adapter) {
			$adapter = $this->app->get($adapter);

			if (!$adapter instanceof AdapterInterface) {
				throw new \RuntimeException(sprintf(
					'Configured adapter "%s" must implement Hiraeth\Routing\AdapterInterface',
					get_class($adapter)
				));
			}

			if (!$adapter->match($this)) {
				continue;
			}

			$this->result = $this->app->run($adapter($this), $parameters);
		}

		foreach ($this->responders as $responder) {
			$responder = $this->app->get($responder);

			if (!$responder instanceof ResponderInterface) {
				throw new \RuntimeException(sprintf(
					'Configured responder "%s" must implement Hiraeth\Routing\ResponderInterface',
					get_class($adapter)
				));
				//
			}

			if (!$responder->match($this)) {
				continue;
			}

			return $responder($this);
		}

		throw new RuntimeException(sprintf(
			'No registered responder matched the result, %s, returned from the current route.',
			!is_object($this->result)
				? var_export($this->result, TRUE)
				: get_class($this->result)
		));
	}


	/**
	 *
	 */
	public function setAdapters(array $adapters): ResolverInterface
	{
		$this->adapters = $adapters;

		return $this;
	}


	/**
	 *
	 */
	public function setResponders(array $responders): ResolverInterface
	{
		$this->responders = $responders;

		return $this;
	}
}
