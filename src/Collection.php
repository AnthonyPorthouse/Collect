<?php

namespace Collect;

class Collection
{
    /** @var \Traversable $data */
    private $data;

    /**
     * Collection constructor.
     *
     * @param array|\Traversable $data The data to create the collection from.
     */
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $data = new \ArrayIterator($data);
        }

        if (is_scalar($data)) {
            $errorMessage = 'data must be an array or otherwise implement \Traversable, got ' . gettype($data);
            throw new \InvalidArgumentException($errorMessage);
        }

        if (!($data instanceof \Traversable)) {
            $errorMessage = 'data must be an array or otherwise implement \Traversable, got ' . get_class($data);
            throw new \InvalidArgumentException($errorMessage);
        }

        $this->data = $data;
    }

    /**
     * Returns a new Collection from the given data. This method allows you to chain directly off the created
     * collection.
     *
     * @param array|\Traversable $data The data to create the collection from
     *
     * @return Collection
     */
    public static function create($data = [])
    {
        return new self($data);
    }

    /**
     * Returns the first element in the collection.
     *
     * @return mixed
     */
    public function first()
    {
        $this->data->rewind();
        return $this->data->current();
    }

    /**
     * Returns the last element in the collection.
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->data);
    }

    /**
     * Adds an element to the collection.
     *
     * @param mixed $element The element to add to the collection
     */
    public function add($element)
    {
        $this->data[] = $element;
    }

    /**
     * Returns a new Collection containing the results of the given function on the data.
     *
     * @param callable $func A function to apply on each element in this collection
     *
     * @return Collection A new collection containing the results of calling the supplied function on the collection
     */
    public function map(callable $func)
    {
        $data = [];
        foreach ($this->data as $element) {
            $data[] = $func($element);
        }

        return new self($data);
    }

    /**
     * Applies the callback to each element in the data.
     *
     * @param callable $func The function to apply to each element in the collection
     */
    public function each(callable $func)
    {
        foreach ($this->data as $element) {
            $func($element);
        }
    }

    /**
     * Returns how many elements are in the collection.
     *
     * @return int How many elements are in the collection
     */
    public function length()
    {
        return count($this->data);
    }
}
