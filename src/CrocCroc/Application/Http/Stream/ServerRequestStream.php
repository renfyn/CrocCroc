<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 30/01/16
 * Time: 09:17
 */

namespace CrocCroc\Application\Http\Stream;

class ServerRequestStream extends AbstractPhpStream
{
    /**
     * @var string
     */
    protected $streamName = 'input';

    protected $mode = 'r';

}