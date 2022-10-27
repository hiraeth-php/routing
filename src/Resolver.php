<?php

namespace Hiraeth\Routing;

use RuntimeException;
use Hiraeth\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * The resolver is responsible for taking a route and turning it into a a response.
 */
class Resolver
{
	/**
	 * @var string[]
	 */
	protected $adapters = array();


	/**
	 * @var Application|null
	 */
	protected $app = NULL;


	/**
	 * @var Request|null
	 */
	protected $request = NULL;


	/**
	 * @var string[]
	 */
	protected $responders = array();


	/**
	 * @var Response|null
	 */
	protected $response = NULL;


	/**
	 * @var mixed
	 */
	protected $target = NULL;


	/**
	 * @var mixed
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
	 * Get the request
	 *
	 * @access public
	 * @return Request The PSR-7 request used to run the resolver
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}


	/**
	 * Get the default response
	 *
	 * @access public
	 * @return Response The PSR-7 response used to run the resolver
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}


	/**
	 * Get the result
	 *
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}


	/**
	 * Get the target
	 *
	 * @return mixed
	 */
	public function getTarget()
	{
		return $this->target;
	}


	/**
	 * Resolve a target returned by `Router::match()` to a PSR-7 response
	 *
	 * @access public
	 * @param Route $route The route to run
	 * @param Request $request The server request that matched the target
	 * @param Response $response The response object to modify for return
	 * @return Response The PSR-7 response from running the target
	 */
	public function run(Route $route, Request $request, Response $response): Response
	{
		$this->target   = $route->getTarget();
		$this->request  = $request;
		$this->response = $response;
		$parameters     = array();

		foreach ($route->getParameters() as $parameter => $value) {
			$parameters[':' . $parameter] = $value;
		}

		foreach ($this->adapters as $adapter) {
			$adapter = $this->app->get($adapter);

			if (!$adapter instanceof Adapter) {
				throw new \RuntimeException(sprintf(
					'Configured adapter "%s" must implement Hiraeth\Routing\Adapter',
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

			if (!$responder instanceof Responder) {
				throw new \RuntimeException(sprintf(
					'Configured responder "%s" must implement Hiraeth\Routing\Responder',
					get_class($responder)
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
	 * @param string[] $adapters A list of adapter classes
	 */
	public function setAdapters(array $adapters): Resolver
	{
		$this->adapters = $adapters;

		return $this;
	}


	/**
	 * @param string[] $responders A list of responder classes
	 */
	public function setResponders(array $responders): Resolver
	{
		$this->responders = $responders;

		return $this;
	}
}
