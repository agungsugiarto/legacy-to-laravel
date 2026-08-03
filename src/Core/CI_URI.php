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

use Illuminate\Http\Request;

class CI_URI
{
    /** @var Request */
    protected $request;

    public function __construct()
    {
        $this->request = Request::capture();
    }

    /**
     * Fetch URI Segment.
     *
     * @see		CI_URI::$segments
     *
     * @param int   $n         Index
     * @param mixed $no_result What to return if the segment index is not found
     *
     * @return mixed
     */
    public function segment($n, $no_result = null)
    {
        return $this->request->segment($n, $no_result);
    }

    /**
     * Segment Array.
     *
     * @return array CI_URI::$segments
     */
    public function segment_array()
    {
        return $this->request->segments();
    }

    /**
     * Total number of segments.
     *
     * @return int
     */
    public function total_segments()
    {
        return count($this->request->segments());
    }

    /**
     * Fetch URI string.
     *
     * @return string CI_URI::$uri_string
     */
    public function uri_string()
    {
        return $this->request->decodedPath();
    }
}
