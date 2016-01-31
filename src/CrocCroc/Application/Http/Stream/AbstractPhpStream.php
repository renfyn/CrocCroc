<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:18
 */

namespace CrocCroc\Application\Http\Stream;

use Psr\Http\Message\StreamInterface;

abstract class AbstractPhpStream implements StreamInterface
{
    /**
     * @var string
     */
    protected $streamName = 'memory';

    protected $mode = 'r+';
    /**
     * parts of the response
     * @var array
     */
    protected $parts = [];
    /**
     * @var bool
     */
    protected $isWritable;
    /**
     * @var bool
     */
    protected $isReadable;
    /**
     * @var bool
     */
    protected $isSeekable;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * AbstractPhpStream constructor.
     */
    public function __construct()
    {
        $fileName  = 'php://' . $this->streamName;

        $this->resource   = fopen( $fileName , $this->mode);

        $meta = stream_get_meta_data($this->resource );

        $this->isSeekable = $meta['seekable'];
        $this->isReadable = is_readable($fileName);
        $this->isWritable = is_writable($fileName);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        if($this->isReadable) {
            return stream_get_contents($this->resource , -1 , 0);
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        fclose($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        $stream = $this->resource;
        $this->resource = null;
        return $stream;
    }

    /**
     * @inheritDoc
     */
    public function getSize()
    {

        if(!is_resource($this->resource)) {
            return null;
        }

        $stats = fstat($this->resource);
        return $stats['size'];


    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
        return ftell($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
        return feof($this->resource);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
        return $this->isSeekable;
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if(!fseek($this->resource , $offset, $whence)) {
            throw new \RuntimeException('unable to seek this stream');
        }
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
        return $this->isWritable;
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
        return $this->isReadable;
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