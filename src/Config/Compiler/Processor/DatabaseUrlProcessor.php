<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Compiler\Processor;

use Smile\GdprDump\Config\Compiler\CompileException;
use Smile\GdprDump\Config\ConfigInterface;
use Smile\GdprDump\Database\DatabaseInterface;

final class DatabaseUrlProcessor implements ProcessorInterface
{
    /**
     * Parse database url (if specified).
     *
     * @throws CompileException
     */
    public function process(ConfigInterface $config): void
    {
        $databaseSettings = (object) $config->get('database');
        $this->processDatabaseNode($databaseSettings);
    }

    /**
     * Parse the database url (if specified).
     *
     * @throws CompileException
     */
    private function processDatabaseNode(object $database): void
    {
        $url = (string) ($database->url ?? '');
        unset($database->url);

        if ($url === '') {
            return;
        }

        // Validate url
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new CompileException(sprintf('The value "%s" is not a valid URL.', $url));
        }

        // Parse url
        $parsedUrl = parse_url($url);
        if ($parsedUrl === false) {
            throw new CompileException(sprintf('Failed to parse the url "%s".', $url));
        }

        // Update database params from parsed url
        $map = [
            'scheme' => 'driver',
            'path' => 'name',
            'host' => 'host',
            'port' => 'port',
            'user' => 'user',
            'pass' => 'password',
        ];

        foreach ($map as $urlPart => $dbParam) {
            if (!array_key_exists($urlPart, $parsedUrl) || array_key_exists($dbParam, $database)) {
                // Parameter was not found in url or is already defined in the database array
                continue;
            }

            $value = (string) $parsedUrl[$urlPart];
            $database->{$dbParam} = match ($dbParam) {
                'name' => ltrim($value, '/'),
                'driver' => $this->getDriverByScheme($value),
                default => $value
            };
        }
    }

    /**
     * Get driver by scheme.
     *
     * @throws CompileException
     */
    private function getDriverByScheme(string $scheme): string
    {
        return match ($scheme) {
            'mysql' => DatabaseInterface::DRIVER_MYSQL,
            default => throw new CompileException(sprintf('Invalid scheme "%s".', $scheme))
        };
    }
}
