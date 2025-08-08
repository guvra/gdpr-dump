<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Validator;

use Smile\GdprDump\Config\ConfigInterface;

interface ValidatorInterface
{
    /**
     * Validate the configuration.
     *
     * @throws ValidationException
     */
    public function validate(ConfigInterface $config): ValidationResultInterface;
}
