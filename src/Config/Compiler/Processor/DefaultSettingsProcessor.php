<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Compiler\Processor;

use stdClass;
use Smile\GdprDump\Config\Config;
use Smile\GdprDump\Config\ConfigInterface;
use Smile\GdprDump\Database\DatabaseInterface;

final class DefaultSettingsProcessor implements ProcessorInterface
{
    /**
     * Add default settings to the configuration.
     */
    public function process(ConfigInterface $config): void
    {
        $defaultConfig = new Config($this->getDefaultSettings());
        $config->extend($defaultConfig);
    }

    /**
     * Get default settings.
     *
     * @return array<string, array<string, mixed>>
     */
    private function getDefaultSettings(): array
    {
        return [
            'database' => (object) [
                'driver' => DatabaseInterface::DRIVER_MYSQL,
            ],
            'dump' => (object) [
                'output' => 'php://stdout',
                'add_drop_database' => false,
                'add_drop_table' => true, // false in MySQLDump-PHP
                'add_drop_trigger' => true,
                'add_locks' => true,
                'complete_insert' => false,
                'compress' => 'none',
                'default_character_set' => 'utf8',
                'disable_keys' => true,
                'events' => false,
                'extended_insert' => true,
                'hex_blob' => false, // true in MySQLDump-PHP
                'init_commands' => [],
                'insert_ignore' => false,
                'lock_tables' => false, // true in MySQLDump-PHP
                'net_buffer_length' => 1000000,
                'no_autocommit' => true,
                'no_create_info' => false,
                'routines' => false,
                'single_transaction' => true,
                'skip_comments' => false,
                'skip_definer' => false,
                'skip_dump_date' => false,
                'skip_triggers' => false,
                'skip_tz_utc' => false,
            ],
            'faker' => (object) [
                'locale' => '',
            ],
            'filter_propagation' => (object) [
                'enabled' => true,
                'ignored_foreign_keys' => [],
            ],
            'tables' => (object) [],
            'tables_whitelist' => [],
            'tables_blacklist' => [],
            'variables' => (object) [],
        ];
    }
}
