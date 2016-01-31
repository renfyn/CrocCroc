<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 24/01/16
 * Time: 08:38
 */

namespace CrocCroc\Application\Http;

use CrocCroc\Application\Http\Message\AbstractMessage;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Response extends AbstractMessage implements ResponseInterface
{
    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        // TODO: Implement getStatusCode() method.
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        // TODO: Implement withStatus() method.
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase()
    {
        // TODO: Implement getReasonPhrase() method.
    }


}