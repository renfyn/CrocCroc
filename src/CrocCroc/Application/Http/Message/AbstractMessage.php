<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 24/01/16
 * Time: 08:40
 */

namespace CrocCroc\Application\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractMessage implements MessageInterface{

    /**
     * list  of supported http versions
     * @var array
     */
    protected $supportedProtocolVersion =
        [
            '0.9',
            '1.0',
            '1.1',
            '2',
        ];
    /**
     * @var string
     */
    protected $protocolVersion;

    /**
     * associative array of all headers
     * @var array
     */
    protected $headers = [];
    /**
     * @var StreamInterface
     */
    protected $body;
    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        if(!in_array($version , $this->supportedProtocolVersion)) {
            throw new \InvalidArgumentException('unsupported http version ' . $version . ' use ' . implode(' or ' , $this->supportedProtocolVersion));
        }

        if($version !== $this->protocolVersion) {

            $newInstance = clone $this;
            $newInstance->protocolVersion = $version;
            return $newInstance;

        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return array_key_exists($name , $this->headers);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        $headerValue = [];
        if(array_key_exists($name , $this->headers)) {

            $headerValue = explode(', ' , $this->headers[$name]);

        }
        return $headerValue;
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        $headerValue = '';
        if(array_key_exists($name , $this->headers)) {

            $headerValue = $this->headers[$name];

        }
        return $headerValue;
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $headers = $this->headers;

        $headers[$name] = $value;

        $instance = clone $this;
        $instance->headers = $headers;
        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {

        $headers = $this->headers;

        if(array_key_exists($name , $headers)) {
            $headers[$name] .= ', ' .  $value;
        } else {
            $headers[$name] = $value;
        }

        $instance = clone $this;
        $instance->headers = $headers;
        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $headers = $this->headers;
        if(array_key_exists($name , $headers)) {
            unset($headers[$name]);
        }
        $instance = clone $this;
        $instance->headers = $headers;
        return $instance;

    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }


}