<?php

namespace Hiraeth\Routing;

use Exception;
use RuntimeException;
use Hiraeth\Http;
use Hiraeth\Application;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;

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
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Request
	 */
	protected $request;


	/**
	 * @var string[]
	 */
	protected $responders = array();


	/**
	 * @var Response
	 */
	protected $response;


	/**
	 * @var Route
	 */
	protected $route;


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
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}


	/**
	 * Get the default response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}


	/**
	 * Get the result
	 */
	public function getResult(): mixed
	{
		return $this->result;
	}


	/**
	 * Get the route
	 */
	public function getRoute(): Route
	{
		return $this->route;
	}


	/**
	 * Get the target
	 */
	public function getTarget(): mixed
	{
		return $this->target;
	}


	/**
	 * Get the mime type for a stream
	 */
	public function getType(StreamInterface $stream, string $default = 'text/plain;charset=UTF-8', bool $default_on_plain = FALSE): string
	{
		$finfo = finfo_open();

		if ($finfo) {
			$mime_type = finfo_buffer($finfo, $stream, FILEINFO_MIME_TYPE);

			finfo_close($finfo);
		}

		if (empty($mime_type) || ($default_on_plain && $mime_type == 'text/plain')) {
			$mime_type = $default;
		}

		return $mime_type;
	}


	/**
	 *  Set the default response into this state
	 */
	public function init(int $code): self
	{
		$this->response = $this->response->withStatus($code);

		return $this;
	}


	/**
	 * Resolve a target returned by `Router::match()` to a PSR-7 response
	 */
	public function run(Route $route, Request $request, Response $response): Response
	{
		$this->route    = $route;
		$this->request  = $request;
		$this->response = $response;

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

			$this->result = $this->app->run($adapter($this), $route->getParameters());
		}

		foreach ($this->responders as $responder) {
			$responder = $this->app->get($responder);

			if (!$responder instanceof Responder) {
				throw new \RuntimeException(sprintf(
					'Configured responder "%s" must implement Hiraeth\Routing\Responder',
					get_class($responder)
				));
			}

			if (!$responder->match($this)) {
				continue;
			}

			try {
				return $responder($this);

			} catch (Exception $e) {
				while ($e->getPrevious()) {
					$e = $e->getPrevious();
				}

				if ($e instanceof Http\Interrupt) {
					return $e->getResponse();
				}

				throw $e;
			}
		}

		throw new RuntimeException(sprintf(
			'No registered responder matched the result, %s, returned from the current route.',
			!is_object($this->result)
				? var_export($this->result, TRUE)
				: get_class($this->result)
		));
	}


	/**
	 * @param class-string[] $adapters A list of adapter classes
	 */
	public function setAdapters(array $adapters): Resolver
	{
		$this->adapters = $adapters;

		return $this;
	}


	/**
	 * @param class-string[] $responders A list of responder classes
	 */
	public function setResponders(array $responders): Resolver
	{
		$this->responders = $responders;

		return $this;
	}
}
