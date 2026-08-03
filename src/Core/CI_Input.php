<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Kenji Suzuki
 * Copyright (c) 2022 Agung Sugiarto.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/kenjis/ci3-to-4-upgrade-helper
 * @see https://github.com/agungsugiarto/legacy-to-laravel
 */

namespace Fluent\Legacy\Core;

use Fluent\Legacy\Exception\NotSupportedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CI_Input
{
    /** @var Request */
    protected $request;

    public function __construct()
    {
        $this->request = Request::capture();
    }

    /**
     * Fetch an item from the GET array.
     *
     * @param mixed $index     Index for item to be fetched from $_GET
     * @param bool  $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function get($index = null, bool $xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->get($index);
    }

    /**
     * Fetch an item from the POST array.
     *
     * @param mixed $index     Index for item to be fetched from $_POST
     * @param bool  $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function post($index = null, bool $xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->post($index);
    }

    /**
     * Fetch an item from POST data with fallback to GET.
     *
     * @param string $index     Index for item to be fetched from $_POST or $_GET
     * @param bool   $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function post_get($index, $xss_clean = false)
    {
        return isset($_POST[$index])
            ? $this->post($index, $xss_clean)
            : $this->get($index, $xss_clean);
    }

    /**
     * Fetch an item from GET data with fallback to POST.
     *
     * @param string $index     Index for item to be fetched from $_GET or $_POST
     * @param bool   $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function get_post($index, $xss_clean = false)
    {
        return isset($_GET[$index])
            ? $this->get($index, $xss_clean)
            : $this->post($index, $xss_clean);
    }

    /**
     * Fetch an item from the COOKIE array.
     *
     * @param mixed $index     Index for item to be fetched from $_COOKIE
     * @param bool  $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function cookie($index = null, $xss_clean = false)
    {
        return $this->request->cookie($index);
    }

    /**
     * Fetch an item from the SERVER array.
     *
     * @param mixed $index     Index for item to be fetched from $_SERVER
     * @param bool  $xss_clean Whether to apply XSS filtering
     *
     * @return mixed
     */
    public function server($index = null, bool $xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->server($index);
    }

    /**
     * Set cookie.
     *
     * Accepts an arbitrary number of parameters (up to 7) or an associative
     * array in the first parameter containing all the values.
     *
     * @param string|mixed[] $name     Cookie name or an array containing parameters
     * @param string         $value    Cookie value
     * @param int            $expire   Cookie expiration time in seconds
     * @param string         $domain   Cookie domain (e.g.: '.yourdomain.com')
     * @param string         $path     Cookie path (default: '/')
     * @param string         $prefix   Cookie name prefix
     * @param bool           $secure   Whether to only transfer cookies via SSL
     * @param bool           $httponly Whether to only makes the cookie accessible via HTTP (no javascript)
     * @param string         $samesite SameSite attribute
     *
     * @return void
     */
    public function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = null, $httponly = null, $samesite = null)
    {
        return Cookie::queue(...func_get_args());
    }

    /**
     * Fetch the IP Address.
     *
     * Determines and validates the visitor's IP address.
     *
     * @return string IP address
     */
    public function ip_address()
    {
        return $this->request->ip();
    }

    /**
     * Validate IP Address.
     *
     * @param string $ip    IP address
     * @param string $which IP protocol: 'ipv4' or 'ipv6'
     *
     * @return bool
     */
    public function valid_ip($ip, $which = '')
    {
        switch (strtolower($which)) {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;
                break;
            default:
                $which = 0;
                break;
        }

        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
    }

    /**
     * Fetch User Agent string.
     *
     * @return string|null User Agent string or NULL if it doesn't exist
     */
    public function user_agent($xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->userAgent();
    }

    /**
     * Request Headers.
     *
     * @param bool $xss_clean Whether to apply XSS filtering
     *
     * @return array
     */
    public function request_headers($xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->header();
    }

    /**
     * Get Request Header.
     *
     * Returns the value of a single member of the headers class member
     *
     * @param string $index     Header name
     * @param bool   $xss_clean Whether to apply XSS filtering
     *
     * @return string|null The requested header on success or NULL on failure
     */
    public function get_request_header($index, $xss_clean = false)
    {
        $this->checkXssClean($xss_clean);

        return $this->request->header($index);
    }

    /**
     * Is AJAX request?
     *
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
     *
     * @return bool
     */
    public function is_ajax_request()
    {
        return $this->request->ajax();
    }

    /**
     * Is CLI request?
     *
     * Test to see if a request was made from the command line.
     *
     * @deprecated	3.0.0	Use is_cli() instead
     *
     * @return bool
     */
    public function is_cli_request()
    {
        return is_cli();
    }

    /**
     * Get Request Method.
     *
     * Return the request method
     *
     * @param bool $upper Whether to return in upper or lower case
     *                    (default: FALSE)
     *
     * @return string
     */
    public function method($upper = false)
    {
        return ($upper)
            ? strtoupper($this->request->method())
            : strtolower($this->request->method());
    }

    /**
     * Magic __get().
     *
     * Allows read access to protected properties
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->request->__get($name);
    }

    private function checkXssClean(bool $xss_clean)
    {
        if ($xss_clean !== false) {
            throw new NotSupportedException(
                '$xss_clean is not supported.'
                    .' Preventing XSS should be performed on output, not input!'
                    .' Use esc() instead.'
            );
        }
    }
}
