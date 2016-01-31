<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:17
 */

namespace CrocCroc\Application\Http\Stream;

class ResponseStream extends AbstractPhpStream
{
    /**
     * @var string
     */
    protected $streamName = 'output';

    protected $mode = 'w+';

}