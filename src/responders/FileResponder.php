<?php

namespace Hiraeth\Routing;

use SplFileInfo;
use Hiraeth\Utils\MimeTypes;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 * {@inheritDoc}
 */
class FileResponder implements Responder
{
	/**
	 * @var MimeTypes|null
	 */
	protected $mimeTypes = NULL;


	/**
	 * @var StreamFactory|null
	 */
	protected $streamFactory = NULL;


	/**
	 *
	 */
	public function __construct(StreamFactory $stream_factory, MimeTypes $mime_types)
	{
		$this->streamFactory = $stream_factory;
		$this->mimeTypes     = $mime_types;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): Response
	{
		$result    = $resolver->getResult();
		$request   = $resolver->getRequest();
		$response  = $resolver->getResponse();
		$mime_type = $this->mimeTypes->getMimeType($result->getExtension());
		$stream    = $this->streamFactory->createStreamFromFile($result->getPathname());

		if (!$mime_type) {
			$mime_type = 'text/plain; charset=UTF-8';
		}

		if (!empty($request->getQueryParams()['download'])) {
			$disposition = sprintf('attachment; filename="%s"', $result->getFileName());
		} else {
			$disposition = sprintf('filename="%s"', $result->getFileName());
		}

		return $response
			->withStatus(200)
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
