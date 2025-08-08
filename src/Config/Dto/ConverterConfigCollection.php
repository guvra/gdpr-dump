<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Dto;

use Smile\GdprDump\Util\Collection;

/**
 * @implements Collection<ConverterConfig>
 */
class ConverterConfigCollection extends Collection
{
    protected string $descriptor = 'converter';
}
