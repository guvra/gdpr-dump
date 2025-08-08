<?php

declare(strict_types=1);

namespace Smile\GdprDump\Util;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use UnexpectedValueException;

/**
 * @template T
 * @implements IteratorAggregate<T>
 */
abstract class Collection implements IteratorAggregate
{
    /**
     * @var T[]
     */
    private array $items = [];
    protected string $descriptor = 'item';

    /**
     * @param T[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**+
     * Add an item to the collection.
     *
     * @param T $item
     */
    public function add(string $index, object $item): self
    {
        $this->items[$index] = $item;

        return $this;
    }

    /**
     * Get an item from the collection.
     *
     * @return T
     * @throws UnexpectedValueException
     */
    public function get(string $index): object
    {
        return $this->has($index)
            ? $this->items[$index]
            : throw new UnexpectedValueException(sprintf('The %s "%s" is not defined.', $this->descriptor, $index));
    }

    /**
     * Check whether an item exists.
     */
    public function has(string $index): bool
    {
        return array_key_exists($index, $this->items);
    }

    /**
     * Get all items.
     *
     * @return T[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return ArrayIterator<string, T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
