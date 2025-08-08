<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Dto;

use Smile\GdprDump\Util\Collection;

/**
 * @implements Collection<TableConfig>
 */
class TableConfigCollection extends Collection
{
    protected string $descriptor = 'table';
}
