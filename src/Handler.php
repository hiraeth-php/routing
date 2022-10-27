<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 * {@inheritDoc}
 */
class Handler implements RequestHandler
{
	/**
	 * @var ResponseFactory|null
	 */
	protected $factory = NULL;


	/**
	 * @var Resolver|null
	 */
	protected $resolver = NULL;


	/**
	 * @var Router|null
	 */
	protected $router = NULL;


	/**
	 *
	 */
	public function __construct(Resolver $resolver, ResponseFactory $factory, Router $router)
	{
		$this->resolver = $resolver;
		$this->factory  = $factory;
		$this->router   = $router;
	}


	/**
	 * {@inheritDoc}
	 */
	public function handle(Request $request): Response
	{
		$response = $this->factory->createResponse(200);
		$route    = $this->router->match($request, $response);

		return $this->resolver->run($route, $request, $response);
	}

}
