<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 24/01/16
 * Time: 20:54
 */

namespace CrocCroc\Application\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    /**
     * contain supported schemes
     * @var array
     */
    protected $validScheme = [
        'tcp',
        'http',
        'https',
    ];

    protected $defaultPort = 80;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $userPass;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var string
     */
    protected $fragment;

    /**
     * @inheritDoc
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority()
    {
        $port = $this->port??$this->defaultPort;

        $authority = $this->host . ':' . $port;

        $userInfo = $this->getUserInfo();
        if(!empty($userInfo)) {
            $authority = $userInfo . '@' . $authority;
        }

        return $authority;

    }

    /**
     * @inheritDoc
     */
    public function getUserInfo()
    {
        if(empty($this->userName) && empty($this->userPass)) {
            return '';
        }
        $userInfo = $this->userName;

        if(!empty($this->userPass)) {
            $userInfo .= ':' . $this->userPass;
        }
        return $userInfo;
    }

    /**
     * @inheritDoc
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function getPort()
    {
        return $this->port??$this->defaultPort;
    }

    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery()
    {
        return http_build_query($this->query , '', '&' , PHP_QUERY_RFC3986);
    }

    /**
     * @inheritDoc
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withScheme($scheme)
    {
        $scheme = strtolower($scheme);

        if(in_array($scheme , $this->validScheme)) {
            if($scheme !== $this->scheme) {
                $newUri = clone $this;
                $newUri->scheme = $scheme;
                return $newUri;
            } else {
                return $this;
            }
        }

        throw new \InvalidArgumentException(' Unsupported scheme ' . $scheme);
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo($user, $password = null)
    {
        if(($this->userPass !== $password) || ($this->userName !== $user)) {
            $clone = clone $this;
            $clone->userName = $user;
            $clone->userPass = $password;
            return $clone;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }

    /**
     * @inheritDoc
     */
    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }

    /**
     * @inheritDoc
     */
    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query)
    {
        // TODO: Implement withQuery() method.
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
    }


}