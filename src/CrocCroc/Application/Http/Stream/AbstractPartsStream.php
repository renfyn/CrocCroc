<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:18
 */

namespace CrocCroc\Application\Http\Stream;

use Psr\Http\Message\StreamInterface;

abstract class AbstractPartsStream implements StreamInterface
{

    protected $parts = [];

    protected $isWritable = false;

    protected $isReadable = false;

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if($this->isReadable) {
            return implode('\n' , $this->parts);
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        // TODO: Implement detach() method.
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        // TODO: Implement tell() method.
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        // TODO: Implement eof() method.
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        // TODO: Implement isSeekable() method.
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        // TODO: Implement seek() method.
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
        // TODO: Implement isWritable() method.
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
        // TODO: Implement write() method.
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
        // TODO: Implement isReadable() method.
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
        // TODO: Implement read() method.
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        // TODO: Implement getContents() method.
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        // TODO: Implement getMetadata() method.
    }


}