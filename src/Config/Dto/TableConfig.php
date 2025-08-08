<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;

class TableConfig
{
    private bool $truncate = false;
    private ?int $limit = null;
    #[SerializedName('order_by')]
    private string $orderBy = '';
    private string $where = '';

    #[SerializedName('converters')]
    private ConverterConfigCollection $convertersConfig;

    public function isTruncate(): bool
    {
        return $this->truncate;
    }

    public function setTruncate(bool $truncate): self
    {
        $this->truncate = $truncate;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getWhere(): string
    {
        return $this->where;
    }

    public function setWhere(string $where): self
    {
        $this->where = $where;

        return $this;
    }

    public function getConvertersConfig(): ConverterConfigCollection
    {
        if (!isset($this->convertersConfig)) {
            $this->convertersConfig = new ConverterConfigCollection();
        }

        return $this->convertersConfig;
    }

    public function setConvertersConfig(ConverterConfigCollection $convertersConfig): self
    {
        $this->convertersConfig = $convertersConfig;

        return $this;
    }
}
