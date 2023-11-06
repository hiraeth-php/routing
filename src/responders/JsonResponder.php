<?php

namespace Hiraeth\Routing;

use Json;
use stdClass;
use Exception;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 * {@inheritDoc}
 */
class JsonResponder implements Responder
{
	/**
	 * @var StreamFactory|null
	 */
	protected $streams = NULL;


	/**
	 *
	 */
	public function __construct(StreamFactory $streams)
	{
		$this->streams = $streams;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): Response
	{
		$result   = $resolver->getResult();
		$response = $resolver->getResponse();
		$content  = Json\Serialize($result);

		if ($content) {
			$stream    = $this->streams->createStream($content);
			$mime_type = $resolver->getType($stream, 'application/json');

			return $response
				->withBody($stream)
				->withHeader('Content-Type', $mime_type)
				->withHeader('Content-Length', (string) $stream->getSize())
			;
		}

		throw new Exception('Failed converting result to JSON');
	}


	/**
	 * {@inheritDoc}
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
