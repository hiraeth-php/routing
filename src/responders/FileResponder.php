<?php

namespace Hiraeth\Routing;

use SplFileInfo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 * {@inheritDoc}
 */
class FileResponder implements Responder
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
		$result    = $resolver->getResult();
		$request   = $resolver->getRequest();
		$response  = $resolver->getResponse();
		$stream    = $this->streams->createStreamFromFile($result->getPathname());
		$mime_type = $resolver->getType($stream);

		if (!empty($request->getQueryParams()['download'])) {
			$disposition = sprintf('attachment; filename="%s"', $result->getFileName());
		} else {
			$disposition = sprintf('filename="%s"', $result->getFileName());
		}

		return $response
			->withBody($stream)
			->withHeader('Content-Type', $mime_type)
			->withHeader('Content-Length', $result->getSize())
			->withHeader('Content-Disposition', $disposition)
		;
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		$result = $resolver->getResult();

		if (!$result instanceof SplFileInfo) {
			return FALSE;
		}

		if ($result->isLink()) {
			$result = new SplFileInfo($result->getRealPath());
		}

		if (!$result->isReadable()) {
			return FALSE;
		}

		if ($result->isDir()) {
			return FALSE;
		}

		return TRUE;
	}
}
