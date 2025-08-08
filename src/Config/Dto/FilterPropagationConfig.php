<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;

class FilterPropagationConfig
{
    private bool $enabled = true;

    /**
     * @var string[]
     */
    #[SerializedName('ignored_foreign_keys')]
    private array $ignoredForeignKeys = [];

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getIgnoredForeignKeys(): array
    {
        return $this->ignoredForeignKeys;
    }

    /**
     * @param string[] $value
     */
    public function setIgnoredForeignKeys(array $foreignKeys): self
    {
        $this->ignoredForeignKeys = $foreignKeys;

        return $this;
    }
}
