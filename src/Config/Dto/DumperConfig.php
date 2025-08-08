<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;

class DumperConfig
{
    /**
     * @var array<string, array|scalar>
     */
    #[SerializedName('database')]
    public array $connectionParams = [];

    /**
     * @var array<string, array|scalar>
     */
    #[SerializedName('dump')]
    public array $dumpSettings = [];

    #[SerializedName('tables')]
    public TableConfigCollection $tablesConfig;

    /**
     * @var string[]
     */
    #[SerializedName('tables_whitelist')]
    public array $includedTables = [];

    /**
     * @var string[]
     */
    #[SerializedName('tables_blacklist')]
    public array $excludedTables = [];

    /**
     * @var array<string, string>
     */
    public array $variables = [];

    #[SerializedPath('[faker][locale]')]
    public string $locale = '';

    #[SerializedName('filter_propagation')]
    public FilterPropagationConfig $filterPropagation;

    /**
     * @return array<string, array|scalar>
     */
    public function getConnectionParams(): array
    {
        return $this->connectionParams;
    }

    /**
     * @param array<string, array|scalar> $value
     */
    public function setConnectionParams(array $params): self
    {
        $this->connectionParams = $params;

        return $this;
    }

    /**
     * @return array<string, array|scalar>
     */
    public function getDumpSettings(): array
    {
        return $this->dumpSettings;
    }

    /**
     * @param array<string, array|scalar> $value
     */
    public function setDumpSettings(array $settings): self
    {
        $this->dumpSettings = $settings;

        return $this;
    }

    public function getTablesConfig(): TableConfigCollection
    {
        if (!isset($this->tablesConfig)) {
            $this->tablesConfig = new TableConfigCollection();
        }

        return $this->tablesConfig;
    }

    public function setTablesConfig(TableConfigCollection $tablesConfig): self
    {
        $this->tablesConfig = $tablesConfig;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIncludedTables(): array
    {
        return $this->includedTables;
    }

    /**
     * @param string[] $value
     */
    public function setIncludedTables(array $tableNames): self
    {
        $this->includedTables = $tableNames;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getExcludedTables(): array
    {
        return $this->excludedTables;
    }

    /**
     * @param array<string, string> $value
     */
    public function setExcludedTables(array $tableNames): self
    {
        $this->excludedTables = $tableNames;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array<string> $value
     */
    public function setVariables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @return FilterPropagationConfig
     */
    public function getFilterPropagation(): FilterPropagationConfig
    {
        if (!isset($this->filterPropagation)) {
            $this->filterPropagation = new FilterPropagationConfig();
        }

        return $this->filterPropagation;
    }

    /**
     * @param FilterPropagationConfig $value
     */
    public function setFilterPropagation(FilterPropagationConfig $filterPropagation): self
    {
        $this->filterPropagation = $filterPropagation;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $value
     */
    public function setLocale(string $value): self
    {
        $this->locale = $value;

        return $this;
    }
}
