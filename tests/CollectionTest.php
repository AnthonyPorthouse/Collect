<?php

use Collect\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testProvider()
    {
        $generator = function () {
            yield 'foo';
            yield 'bar';
        };

        return [
            [['foo', 'bar']],
            [new ArrayIterator(['foo', 'bar'])],
            [$generator()],
            [$generator],
        ];
    }

    /**
     * @dataProvider testProvider
     */
    public function testCreation($data)
    {
        $collection = new Collection($data);

        $this->assertEquals('foo', $collection->first());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionOnScalarData()
    {
        $collection = new Collection(1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionOnNoneGeneratorClosure()
    {
        $collection = new Collection(function () {
            return 2;
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionOnNoneTraversibleObject()
    {
        $collection = new Collection((object) []);
    }

    /**
     * @dataProvider testProvider
     */
    public function testChainableConstruction($data)
    {
        $first = Collection::create($data)->first();

        $this->assertEquals('foo', $first);
    }

    /**
     * @dataProvider testProvider
     */
    public function testFirstReturnsFirstElement($data)
    {
        $collection = new Collection($data);

        $this->assertEquals('foo', $collection->first());
    }

    /**
     * @dataProvider testProvider
     */
    public function testLastReturnsLastElement($data)
    {
        $collection = new Collection($data);

        $this->assertEquals('bar', $collection->last());
    }

    /**
     * @dataProvider testProvider
     */
    public function testAddAddsNewElement($data)
    {
        $collection = new Collection($data);
        $collection = $collection->add('baz');

        $this->assertEquals('baz', $collection->last());
    }

    /**
     * @dataProvider testProvider
     */
    public function testLengthIsCorrect($data)
    {
        $collection = new Collection($data);
        $this->assertEquals(2, $collection->length());

        $collection = $collection->add('baz');
        $this->assertEquals(3, $collection->length());
    }

    /**
     * @dataProvider testProvider
     */
    public function testMapReturnsANewCollection($data)
    {
        $collection = new Collection($data);

        $newCollection = $collection->map(function ($element) {
            return strtoupper($element);
        });

        $this->assertNotSame($collection, $newCollection);
        $this->assertInstanceOf(Collection::class, $newCollection);
        $this->assertEquals('FOO', $newCollection->first());
    }

    /**
     * @dataProvider testProvider
     */
    public function testFilteringElements($data)
    {
        $collection = new Collection($data);

        $filteredResults = $collection->filter(function ($el) {
            return substr($el, 0, 1) === 'b';
        });

        $this->assertEquals(1, $filteredResults->length());
        $this->assertEquals('bar', $filteredResults->first());
    }
}
