<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config;

interface ConfigInterface
{
    /**
     * Get a config value.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a config item.
     */
    public function set(string $key, mixed $value): self;

    /**
     * Check whether a key is defined in the config.
     */
    public function has(string $key): bool;

    /**
     * Get the root item.
     */
    public function getRoot(): object;

    /**
     * Reset the config items.
     */
    public function reset(array $items): self;

    /**
     * Merge the items of this config with the items of the specified config.
     *
     * Objects properties are merged recursively, array and scalar values are replaced.
     */
    //public function merge(object $object): self;
    public function merge(ConfigInterface $config): self;

    /**
     * Same as merge but reversed (config items are overridden).
     */
    public function extend(ConfigInterface $config): self;
}
