<?php

use Collect\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testFirstReturnsFirstElement()
    {
        $collection = new Collection(['foo', 'bar']);

        $this->assertEquals('foo', $collection->first());
    }

    public function testLastReturnsLastElement()
    {
        $collection = new Collection(['foo', 'bar']);

        $this->assertEquals('bar', $collection->last());
    }

    public function testAddAddsNewElement()
    {
        $collection = new Collection(['foo', 'bar']);
        $collection->add('baz');

        $this->assertEquals('baz', $collection->last());
    }
}
