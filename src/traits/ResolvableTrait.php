<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 *
 */
trait ResolvableTrait
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Routing\Resolver
	 */
	protected $resolver;

	/**
	 * @var Response
	 */
	protected $response;


	/**
	 * Set the resolver (and implicitely the default request/response)
	 */
	public function setResolver(Resolver $resolver): self
	{
		$this->request  = $resolver->getRequest();
		$this->response = $resolver->getResponse();
		$this->resolver = $resolver;

		return $this;
	}
}
