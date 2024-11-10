<?php

namespace Hiraeth\Routing;

use Exception;
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
	 *
	 */
	protected function init(Exception|int $code): static
	{
		if ($code instanceof Exception) {
			$code = $code->getCode();
		}

		$this->resolver->init($code);

		return $this;
	}


	/**
	 * Set the resolver (and implicitely the default request/response)
	 */
	public function setResolver(Resolver $resolver): static
	{
		$this->request  = $resolver->getRequest();
		$this->response = $resolver->getResponse();
		$this->resolver = $resolver;

		return $this;
	}
}
