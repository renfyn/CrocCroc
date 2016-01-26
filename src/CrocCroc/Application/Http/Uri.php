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
    protected $user;

    /**
     * @var string
     */
    protected $pass;

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
     * Uri constructor.
     *
     * @param string $uriString
     */
    public function __construct(string $uriString = null) {


        if(is_null($uriString)) {
            return;
        }
        if(filter_var($uriString , FILTER_VALIDATE_URL)) {

            $params = parse_url($uriString);

            foreach($params as $property => $value) {
                if($property === 'query') {
                    parse_str($value , $this->$property );
                } else {
                    $this->$property = $value;
                }
            }

            return;
        }

        throw new \InvalidArgumentException(' invalid uri : ' . $uriString);

    }

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
        if(empty($this->user) && empty($this->pass)) {
            return '';
        }
        $userInfo = $this->user;

        if(!empty($this->pass)) {
            $userInfo .= ':' . $this->pass;
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
        if(($this->pass !== $password) || ($this->user !== $user)) {
            $clone = clone $this;
            $clone->user = $user;
            $clone->pass = $password;
            return $clone;
        }
        return $this;
    }

    /**
     * verify if host is a valid domain name without check dns entries.
     *
     * @param $host
     * @return bool
     */
    protected function isValidDomainName($host) {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host) //valid chars check
            && preg_match("/^.{1,253}$/", $host) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $host)   ); //length of each label
    }

    /**
     * @inheritDoc
     */
    public function withHost($host)
    {
        if($this->isValidDomainName($host) || filter_var($host , FILTER_VALIDATE_IP)) {

            if($host !== $this->host) {

                $instance = clone $this;
                $instance->host = $host;
                return $instance;
            } else {
                return $this;
            }

        }

        throw new \InvalidArgumentException(' invalid host ' . $host);
    }

    /**
     * @inheritDoc
     */
    public function withPort($port)
    {
        $portRegEx = '^(6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{2}|6[0-4][0-9]{3}|[1-5][0-9]{4}|[1-9][0-9]{1,3}|[0-9])$';

        if(is_int($port) &&  preg_match('/' . $portRegEx . '/i' , $port)) {

            if($port !== $this->port) {

                $instance = clone $this;
                $instance->port = $port;
                return $instance;
            } else {
                return $this;
            }

        }


        throw new \InvalidArgumentException(' invalid port ' . $port);
    }

    /**
     * @inheritDoc
     */
    public function withPath($path)
    {
        if(is_string($path) &&  preg_match('/^\//i' , $path)) {

            if($path !== $this->path) {

                $instance = clone $this;
                $instance->path = $path;
                return $instance;
            } else {
                return $this;
            }

        }


        throw new \InvalidArgumentException(' invalid path ' . $path . 'it must be a string and be absolute');
    }

    /**
     * @inheritDoc
     */
    public function withQuery($query)
    {
        $pattern = "^([a-zA-Z0-9&\*\%\=\|])*$";
        if(is_string($query) &&  preg_match('/' . $pattern . '/i' , $query)) {

            if($query !== http_build_query($this->query, '', '&' , PHP_QUERY_RFC3986)) {

                $instance = clone $this;
                parse_str($query , $instance->query );
                return $instance;
            } else {
                return $this;
            }

        }


        throw new \InvalidArgumentException(' invalid query ' . $query . 'it must be url encoded see RFC-3986');
    }

    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        if($fragment !== $this->fragment) {

            $instance = clone $this;
            $instance->fragment = $fragment;
            return $instance;
        } else {
            return $this;
        }
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $uri = '';

        if(!empty($this->getScheme())) {

            $uri .= $this->getScheme() . ':';
        }
        $uri .= '//';
        if(!empty($this->getUserInfo())) {
            $uri .= $this->getUserInfo() . '@';
        }

        $uri .= $this->getHost();

        if(!empty($this->getPort()) && $this->getPort() !== 80) {
            $uri .= ':' . $this->getPort();
        }

        $uri .= $this->getPath();

        if(!empty($this->getQuery())) {
            $uri .= '?' . $this->getQuery();
        }

        if(!empty($this->getFragment())) {
            $uri .= '#' . $this->getFragment();
        }

        return $uri;

    }


}