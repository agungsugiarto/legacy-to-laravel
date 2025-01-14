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

namespace Fluent\Legacy\Core\Loader;

use Fluent\Legacy\Database;
use Fluent\Legacy\Database\CI_DB;
use Fluent\Legacy\Database\CI_DB_forge;

class DatabaseLoader
{
    /** @var ControllerPropertyInjector */
    private $injector;

    /** @var CI_DB */
    private $db;

    /** @var CI_DB_forge */
    private $dbforge;

    public function __construct(ControllerPropertyInjector $injector)
    {
        require_once __DIR__.'../../../database/DB.php';

        $this->injector = $injector;
    }

    public function load($params = '', $return = false, $query_builder = null)
    {
        if (
            $return === false && $query_builder === null
            && isset($this->db)
        ) {
            return false;
        }

        if ($return) {
            return Database\DB($params, $query_builder);
        }

        if ($this->db === null) {
            $this->db = Database\DB($params, $query_builder);
            $this->injector->inject('db', $this->db);
        }

        return false;
    }

    public function loadDbForge(?object $db, bool $return): CI_DB_forge
    {
        if ($return) {
            return new CI_DB_forge($db);
        }

        if ($this->dbforge === null) {
            $this->dbforge = new CI_DB_forge();
            $this->injector->inject('dbforge', $this->dbforge);
        }

        return $this->dbforge;
    }
}
