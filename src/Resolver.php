<?php

namespace Hiraeth\Routing;

use Hiraeth\Broker;
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
	protected $result = NULL;


	/**
	 *
	 */
	public function __construct(Broker $broker)
	{
		$this->broker = $broker;
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
	 * @param Request $request The server request that matched the target
	 * @param Response $response The response object to modify for return
	 * @param mixed $target The target to construct and/or run
	 * @return Response The PSR-7 response from running the target
	 */
	public function run(Request $request, Response $response, $target): Response
	{
		$this->parameters = [];
		$this->response   = $response;
		$this->request    = $request;
		$this->target     = $target;

		foreach ($this->adapters as $adapter) {
			$adapter = $this->broker->make($adapter);

			if (!$adapter instanceof AdapterInterface) {
				throw new \RuntimeException(sprintf(
					'Configured adapter "%s" must implement Hiraeth\Routing\AdapterInterface',
					get_class($adapter)
				));
			}

			if (!$adapter->match($this)) {
				continue;
			}

			$this->result = $this->broker->execute($adapter($this), $this->parameters);
		}

		foreach ($this->responders as $responder) {
			$responder = $this->broker->make($responder);

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
			var_export($this->result, TRUE)
		));
	}


	/**
	 *
	 */
	public function setAdapters(array $adapters): object
	{
		$this->adapters = $adapters;

		return $this;
	}


	/**
	 *
	 */
	public function setParameters(array $parameters): object
	{
		foreach ($parameters as $parameter => $value) {
			$this->parameters[':' . $parameter] = $value;
		}

		return $this;
	}


	/**
	 *
	 */
	public function setResponders(array $responders): object
	{
		$this->responders = $responders;

		return $this;
	}
}
