<?php

namespace Collect;

class Collection
{
    /** @var \IteratorIterator $data */
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
            $errorMessage = 'data must be an array or otherwise implement \Traversable, got '.gettype($data);
            throw new \InvalidArgumentException($errorMessage);
        }

        if ($data instanceof \Closure) {
            $reflection = new \ReflectionFunction($data);

            if (!$reflection->isGenerator()) {
                $errorMessage = 'data must be an array or otherwise implement \Traversable, got '.get_class($data);
                throw new \InvalidArgumentException($errorMessage);
            }

            $data = $data();
        }

        if ($data instanceof \Generator) {
            $newData = [];
            foreach ($data as $el) {
                $newData[] = $el;
            }

            $data = new \ArrayIterator($newData);
            unset($newData);
        }

        if (!($data instanceof \Traversable)) {
            $errorMessage = 'data must be an array or otherwise implement \Traversable, got '.get_class($data);
            throw new \InvalidArgumentException($errorMessage);
        }

        $this->data = new \IteratorIterator($data);
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
        $this->data->rewind();
        while ($this->data->valid()) {
            $val = $this->data->current();
            $this->data->next();
        }

        return $val;
    }

    /**
     * Returns a new Collection with $element added to the end.
     *
     * @param mixed $element The element to add to the collection
     *
     * @return Collection
     */
    public function add($element)
    {
        $data = [];

        $this->each(function ($el) use (&$data) {
            $data[] = $el;
        });

        $data[] = $element;

        return new self($data);
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
        $count = 0;

        $this->data->rewind();
        while ($this->data->valid()) {
            $count++;
            $this->data->next();
        }

        return $count;
    }
}
