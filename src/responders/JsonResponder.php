<?php

namespace Hiraeth\Routing;

use stdClass;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 *
 */
class JsonResponder implements Responder
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
		$result   = $resolver->getResult();
		$response = $resolver->getResponse();
		$stream   = $this->streamFactory->createStream(json_encode($result));

		return $response
			->withStatus(200)
			->withBody($stream)
			->withHeader('Content-Type', 'application/json')
		;
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		$result = $resolver->getResult();

		if (is_array($result)) {
			return TRUE;
		}

		if (is_object($result)) {
			if ($result instanceof JsonSerializable) {
				return TRUE;
			}

			if ($result instanceof stdClass) {
				return TRUE;
			}
		}

		return FALSE;
	}
}
