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

    public function testLengthIsCorrect()
    {
        $collection = new Collection(['foo', 'bar']);
        $this->assertEquals(2, $collection->length());

        $collection->add('baz');
        $this->assertEquals(3, $collection->length());
    }

    public function testMapReturnsANewCollection()
    {
        $collection = new Collection(['foo', 'bar']);

        $newCollection = $collection->map(function ($element) {
            return strtoupper($element);
        });

        $this->assertNotSame($collection, $newCollection);
        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertEquals('FOO', $newCollection->first());
    }
}
