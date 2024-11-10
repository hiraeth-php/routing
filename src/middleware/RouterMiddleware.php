<?php

namespace Hiraeth\Routing;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 *
 */
class RouterMiddleware implements MiddlewareInterface
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
	public function __construct(Router $router = NULL, Resolver $resolver, ResponseFactory $factory)
	{
		$this->router   = $router;
		$this->resolver = $resolver;
		$this->factory  = $factory;
	}


	/**
	 *
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		$response = $this->factory->createResponse(200);

		if ($this->router) {
			return $this->resolver->run(
				$this->router->match($request, $response),
				$request,
				$response
			);
		}

		return $this->resolver->run(
			new Route(
				function(Resolver $resolver) {
					return $resolver->getResponse()->withStatus(404);
				}
			),
			$request,
			$response
		);
	}
}
