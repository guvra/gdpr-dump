<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config;

final class Config implements ConfigInterface
{
    private object $root;

    public function __construct(private array $items = [])
    {
        $this->reset($items);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->root->$key : $default;
    }

    public function set(string $key, mixed $value): self
    {
        $this->root->{$key} = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return property_exists($this->root, $key);
    }

    public function getRoot(): object
    {
        return clone $this->root;
    }

    public function reset(array $items = []): self
    {
        $this->root = (object) $items;

        return $this;
    }

    public function merge(ConfigInterface $config): self
    {
        $root = $this->getRoot(); // get a cloned root
        $this->mergeObjects($root, $config->getRoot());
        $this->root = $root;

        return $this;
    }

    public function extend(ConfigInterface $config): self
    {
        $root = $config->getRoot(); // get a clone root
        $this->mergeObjects($root, $this->root);
        $this->root = $root;

        return $this;
    }

    /**
     * Merge two objects. Properties of nested objects are merged, other types are replaced (scalar, array...).
     */
    private function mergeObjects(object $object, object $override): void
    {
        foreach ($override as $property => $value) {
            if (!property_exists($object, $property)) {
                // New property, add it to the existing object
                $object->{$property} = $value;
                continue;
            }

            if (is_object($object->{$property})) {
                if (is_object($value)) {
                    // Merge values of the two objects
                    $this->mergeObjects($object->{$property}, $value);

                    // If the merged object is empty, remove it (generates a cleaner config)
                    if (/*(array) $value && */!((array) $object->{$property})) {
                        unset($object->{$property});
                    }
                    continue;
                }

                if ($value === null) {
                    // Allow object removal by setting the value to null
                    unset($object->{$property});
                    continue;
                }
            }

            // Overwrite existing value
            $object->{$property} = $value;
        }
    }
}
