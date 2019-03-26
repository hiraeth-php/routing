<?php

namespace Hiraeth\Routing;

use StdClass;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 *
 */
class JsonResponder implements ResponderInterface
{
	/**
	 *
	 */
	protected $streamFactory = NULL;


	/**
	 *
	 */
	public function __construct(StreamFactory $stream_factory)
	{
		$this->streamFactory = $stream_factory;
	}


	/**
	 *
	 */
	public function __invoke(Resolver $resolver): Response
	{
		return $resolver->getResponse()
			->withHeader('Content-Type', 'application/json')
			->withBody(
				$this->streamFactory->createStream(json_encode($resolver->getResult()))
			)
		;
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		$result = $resolver->getResult();

		return is_array($result) || (
			is_object($result) && (
				$result instanceof JsonSerializable
				|| $result instanceof StdClass
			)
		);
	}
}
