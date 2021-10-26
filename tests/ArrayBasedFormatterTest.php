<?php

declare(strict_types=1);

namespace Crell\Serde;

use Crell\Serde\Records\MappedCollected\ThingA;
use Crell\Serde\Records\MappedCollected\ThingB;
use Crell\Serde\Records\MappedCollected\ThingC;
use Crell\Serde\Records\Shapes\Circle;

abstract class ArrayBasedFormatterTest extends SerdeTest
{
    abstract protected function arrayify(mixed $serialized): array;

    protected function point_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(1, $toTest['x']);
        self::assertEquals(2, $toTest['y']);
        self::assertEquals(3, $toTest['z']);
    }

    protected function visibility_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(1, $toTest['public']);
        self::assertEquals(2, $toTest['protected']);
        self::assertEquals(3, $toTest['private']);
        self::assertEquals(4, $toTest['visibility']['public']);
        self::assertEquals(5, $toTest['visibility']['protected']);
        self::assertEquals(6, $toTest['visibility']['private']);
    }

    protected function optional_point_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(1, $toTest['x']);
        self::assertEquals(2, $toTest['y']);
        self::assertEquals(0, $toTest['z']);
    }

    protected function all_fields_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);
        //        var_dump($serialized);
    }

    protected function name_mangling_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('Larry', $toTest['renamed']);
        self::assertEquals('value', $toTest['TOUPPER']);
        self::assertEquals('value', $toTest['tolower']);
        self::assertEquals('value', $toTest['beep_prefix']);
    }

    protected function flattening_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('Larry', $toTest['first']);
        self::assertEquals('Garfield', $toTest['last']);
        self::assertEquals('A', $toTest['a']);
        self::assertEquals(2, $toTest['b']);
        self::assertEquals('C', $toTest['c']);
    }

    protected function custom_object_reader_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('huge', $toTest['task']['name']);
        self::assertEquals('big', $toTest['task']['size']);
    }

    protected function custom_type_map_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('huge', $toTest['task']['name']);
        self::assertEquals('big', $toTest['task']['size']);
    }

    protected function typemap_on_parent_class_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(3, $toTest['aShape']['radius']);
        self::assertEquals('circle', $toTest['aShape']['shape']);
    }

    protected function root_type_map_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('b', $toTest['type']);
        self::assertEquals('Bob', $toTest['name']);
    }

    public function nested_objects_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('First', $toTest['name']);
        self::assertEquals('Second', $toTest['child']['name']);
        self::assertEquals('Third', $toTest['child']['child']['name']);
        self::assertEquals('Fourth', $toTest['child']['child']['child']['name']);
    }

    protected function nested_objects_with_flattening_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        $testFlattened = static function ($arr) {
            self::assertEquals('A', $arr['a']);
            self::assertEquals('B', $arr['b']);
        };

        self::assertEquals('First', $toTest['name']);
        $testFlattened($toTest);
        self::assertEquals('Second', $toTest['child']['name']);
        $testFlattened($toTest['child']);
        self::assertEquals('Third', $toTest['child']['child']['name']);
        $testFlattened($toTest['child']['child']);
        self::assertEquals('Fourth', $toTest['child']['child']['child']['name']);
        $testFlattened($toTest['child']['child']['child']);
    }

    protected function empty_values_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('narf', $toTest['nonConstructorDefault']);
        self::assertEquals('beep', $toTest['required']);
        self::assertEquals('boop', $toTest['withDefault']);
        self::assertArrayNotHasKey('nullableUninitialized', $toTest);
        self::assertArrayNotHasKey('uninitialized', $toTest);
        self::assertArrayNotHasKey('roNullable', $toTest);
    }

    public function exclude_values_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals('one', $toTest['one']);
        self::assertArrayNotHasKey('two', $toTest);
    }

    protected function classname_typemap_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(Circle::class, $toTest['aShape']['class']);
    }

    public function mapped_collected_dictionary_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(ThingA::class, $toTest['A']['class']);
        self::assertEquals(ThingB::class, $toTest['B']['class']);
        self::assertEquals(ThingC::class, $toTest['C']['class']);
    }

    public function pagination_flatten_object_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(500, $toTest['total']);
        self::assertEquals(40, $toTest['offset']);
        self::assertEquals(10, $toTest['limit']);
        self::assertEquals('Widget', $toTest['products'][0]['name']);
    }

    public function native_object_serialization_validate(mixed $serialized): void
    {
        $toTest = $this->arrayify($serialized);

        self::assertEquals(1, $toTest['a2']);
        self::assertEquals('beep', $toTest['b2']);
        self::assertEquals('1918-11-11T11:11:11.000-06:00', $toTest['c2']);
    }
}
