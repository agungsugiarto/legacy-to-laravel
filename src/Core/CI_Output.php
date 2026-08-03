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
use Illuminate\Http\Response;

class CI_Output
{
    /**
     * @var Response
     */
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Set Header.
     *
     * Lets you set a server header which will be sent with the final output.
     *
     * Note: If a file is cached, headers will not be sent.
     *
     * @param string $header  Header
     * @param bool   $replace Whether to replace the old header value, if already set
     *
     * @todo    We need to figure out how to permit headers to be cached.
     */
    public function set_header(string $header, bool $replace = true): self
    {
        [$name, $value] = explode(':', $header, 2);

        if ($replace) {
            $this->response->header($name, trim($value));

            return $this;
        }

        $this->response->header($name, trim($value));

        return $this;
    }

    /**
     * Get Output.
     *
     * Returns the current output string.
     */
    public function get_output(): string
    {
        return $this->response->getContent();
    }

    /**
     * Set Output.
     *
     * Sets the output string.
     *
     * @param string $output Output data
     *
     * @return CI_Output
     */
    public function set_output($output)
    {
        $this->response->setContent($output);

        return $this;
    }

    /**
     * Set Content-Type Header.
     *
     * @param string $mime_type Extension of the file we're outputting
     * @param string $charset   Character set (default: NULL)
     *
     * @return CI_Output
     */
    public function set_content_type($mime_type, $charset = null)
    {
        $this->response->header($mime_type, $charset);

        return $this;
    }

    /**
     * Get Header.
     *
     * @param string $header
     *
     * @return string
     */
    public function get_header($header)
    {
        return $this->response->headers->get($header);
    }

    /**
     * Set HTTP Status Header.
     *
     * As of version 1.7.2, this is an alias for common function
     * set_status_header().
     *
     * @param int    $code Status code (default: 200)
     * @param string $text Optional message
     *
     * @return CI_Output
     */
    public function set_status_header($code = 200, $text = '')
    {
        $this->response->setStatusCode($code, $text);

        return $this;
    }

    /**
     * Enable/disable Profiler.
     */
    public function enable_profiler(): self
    {
        throw new NotSupportedException('enable_profiler() is not supported.');
    }
}
