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
        try {
            if(!fseek($this->resource , $offset, $whence) === 0) {
                throw new \RuntimeException('unable to seek this stream');
            }
        } catch(\Exception $e) {
            throw new \RuntimeException('unable to seek this stream ' . $e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        try {
            if(!rewind($this->resource)) {
                throw new \RuntimeException('unable to rewind this stream');
            }
        } catch(\Exception $e) {
            throw new \RuntimeException('unable to rewind this stream');
        }
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
        if($this->isWritable) {
            $len = fwrite($this->resource , $string);
            return  $len;
        }
        throw new \RuntimeException('unable to write this stream');

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
        if($this->isReadable) {
            try {
                return fread($this->resource, $length);
            } catch(\Exception $e) {
                throw new \RuntimeException('unable to read this stream : ' . $e->getMessage());
            }
        }
        throw new \RuntimeException('unable to read this stream');
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
        if($this->isReadable) {
            try {
                return stream_get_contents($this->resource , -1 , 0);
            } catch(\Exception $e) {
                throw new \RuntimeException('unable to read this stream : ' . $e->getMessage());
            }
        }
        throw new \RuntimeException('unable to read this stream');
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
        $metadata = stream_get_meta_data($this->resource);

        if(is_null($key)) {
            return $metadata;
        }
        if(array_key_exists($key , $metadata)) {
            return $metadata[$key];
        }

        return null;
    }


}