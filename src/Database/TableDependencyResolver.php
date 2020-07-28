<?php

declare(strict_types=1);

namespace Smile\GdprDump\Database;

use Smile\GdprDump\Database\Metadata\Definition\Constraint\ForeignKey;
use Smile\GdprDump\Database\Metadata\MetadataInterface;

class TableDependencyResolver
{
    /**
     * @var MetadataInterface
     */
    private MetadataInterface $metadata;

    /**
     * @var ForeignKey[][]
     */
    private array $foreignKeys = [];

    /**
     * @var bool
     */
    private bool $resolved = false;

    /**
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get the foreign keys that are related to the specified table.
     *
     * e.g.
     * - with $tableName as "table1"
     * - with foreign keys as: table2 with FK to table 1, table 3 with FK to table 2
     *
     * Result will be:
     * ```
     * [
     *    'table2' => [FK of table 2 to table 1]
     *    'table3' => [FK of table 3 to table 2]
     * ]
     * ```
     *
     * @param string $tableName
     * @return array
     */
    public function getTableDependencies(string $tableName): array
    {
        $this->buildDependencyTree();

        return $this->getDependencies($tableName);
    }

    /**
     * Get the foreign keys that are related to the specified tables.
     *
     * @param array $tableNames
     * @return array
     */
    public function getTablesDependencies(array $tableNames): array
    {
        $this->buildDependencyTree();

        $dependencies = [];
        foreach ($tableNames as $tableName) {
            $dependencies = $this->getDependencies($tableName, $dependencies);
        }

        return $dependencies;
    }

    /**
     * Recursively fetch all dependencies related to a table.
     *
     * @param string $tableName
     * @param array $dependencies
     * @return array
     */
    private function getDependencies(string $tableName, array $dependencies = []): array
    {
        // No foreign key to this table
        if (!isset($this->foreignKeys[$tableName])) {
            return $dependencies;
        }

        $foreignKeys = $this->foreignKeys[$tableName];

        foreach ($foreignKeys as $foreignKey) {
            $dependencyTable = $foreignKey->getLocalTableName();

            // Detect cyclic dependencies
            if ($dependencyTable === $tableName) {
                continue;
            }

            $dependencies[$dependencyTable][$tableName] = $foreignKey;
            $dependencies = $this->getDependencies($dependencyTable, $dependencies);
        }

        return $dependencies;
    }

    /**
     * Build the tables dependencies (parent -> children).
     */
    private function buildDependencyTree(): void
    {
        if ($this->resolved) {
            return;
        }

        $tableNames = $this->metadata->getTableNames();

        foreach ($tableNames as $tableName) {
            $foreignKeys = $this->metadata->getForeignKeys($tableName);

            foreach ($foreignKeys as $foreignKey) {
                $foreignTableName = $foreignKey->getForeignTableName();
                $this->foreignKeys[$foreignTableName][] = $foreignKey;
            }
        }

        $this->resolved = true;
    }
}
