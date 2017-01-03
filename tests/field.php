<?php

use PHPUnit\Framework\TestCase;

use jugger\data\field\AnyField;
use jugger\data\field\BoolField;
use jugger\data\field\EnumField;
use jugger\data\field\FloatField;
use jugger\data\field\IntField;
use jugger\data\field\TextField;

class FieldTest extends TestCase
{
    public function testBase()
    {
        $field = new AnyField([
            'name' => 'test field'
        ]);
        $this->assertEquals($field->getName(), 'test field');
        $this->assertNull($field->getValue());

        $field = new AnyField([
            'name' => 'test field',
            'value' => 'default',
        ]);
        $this->assertEquals($field->getValue(), 'default');

        $field->setValue(null);
        $this->assertNull($field->getValue());

        try {
            $field = new AnyField();
        }
        catch (Exception $ex) {
            // not found 'name' property
        }

        $this->assertNotNull($ex);
    }

    public function testInteger()
    {
        $field = new IntField(['name' => 'test field']);

        $field->setValue(123);
        $this->assertEquals($field->getValue(), 123);

        $field->setValue(-123.45);
        $this->assertEquals($field->getValue(), -123);

        $field->setValue(true);
        $this->assertEquals($field->getValue(), 1);

        $field->setValue(false);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue([]);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue([1,2,3]);
        $this->assertEquals($field->getValue(), 1);

        $field->setValue(new stdClass);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('123');
        $this->assertEquals($field->getValue(), 123);

        $field->setValue('-123.45');
        $this->assertEquals($field->getValue(), -123);

        $field->setValue('true');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('false');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('osfh923hiuerlg');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('16236 tjsdnfsdf');
        $this->assertEquals($field->getValue(), 16236);
    }

    public function testFloat()
    {
        $field = new FloatField(['name' => 'test field']);

        $field->setValue(123);
        $this->assertEquals($field->getValue(), 123);

        $field->setValue(-123.45);
        $this->assertEquals($field->getValue(), -123.45);

        $field->setValue(true);
        $this->assertEquals($field->getValue(), 1);

        $field->setValue(false);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue([]);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue([1,2,3]);
        $this->assertEquals($field->getValue(), 1);

        $field->setValue(new stdClass);
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('123');
        $this->assertEquals($field->getValue(), 123);

        $field->setValue('-123.45');
        $this->assertEquals($field->getValue(), -123.45);

        $field->setValue('true');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('false');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('osfh923hiuerlg');
        $this->assertEquals($field->getValue(), 0);

        $field->setValue('16236 tjsdnfsdf');
        $this->assertEquals($field->getValue(), 16236);
    }

    public function testText()
    {
        $field = new TextField(['name' => 'test field']);

        $field->setValue(123);
        $this->assertEquals($field->getValue(), '123');

        $field->setValue(-123.45);
        $this->assertEquals($field->getValue(), '-123.45');

        $field->setValue(true);
        $this->assertEquals($field->getValue(), 'true');

        $field->setValue(false);
        $this->assertEquals($field->getValue(), 'false');

        $field->setValue([]);
        $this->assertEquals($field->getValue(), '[]');

        $field->setValue([1,2,3]);
        $this->assertEquals($field->getValue(), '[1,2,3]');

        $field->setValue(new stdClass);
        $this->assertEquals($field->getValue(), '{}');

        $field->setValue((object) ['id' => 1, 'name' => 'test']);
        $this->assertEquals($field->getValue(), '{"id":1,"name":"test"}');

        $field->setValue('16236 tjsdnfsdf');
        $this->assertEquals($field->getValue(), '16236 tjsdnfsdf');
    }

    public function testBool()
    {
        $field = new BoolField(['name' => 'test field']);

        $field->setValue(0);
        $this->assertFalse($field->getValue());

        $field->setValue(123);
        $this->assertTrue($field->getValue());

        $field->setValue(-123.45);
        $this->assertTrue($field->getValue());

        $field->setValue(true);
        $this->assertTrue($field->getValue());

        $field->setValue(false);
        $this->assertFalse($field->getValue());

        $field->setValue([]);
        $this->assertFalse($field->getValue());

        $field->setValue([1,2,3]);
        $this->assertTrue($field->getValue());

        $field->setValue(new stdClass);
        $this->assertTrue($field->getValue());

        $field->setValue('123');
        $this->assertTrue($field->getValue());

        $field->setValue('-123.45');
        $this->assertTrue($field->getValue());

        $field->setValue('');
        $this->assertFalse($field->getValue());

        $field->setValue('false');
        $this->assertTrue($field->getValue());

        $field->setValue('osfh923hiuerlg');
        $this->assertTrue($field->getValue());

        $field->setValue('16236 tjsdnfsdf');
        $this->assertTrue($field->getValue());
    }

    public function testEnum()
    {
        $field = new EnumField([
            'name' => 'test field',
            'values' => [
                'man', 'woman', 'it'
            ],
        ]);

        $values = $field->getAvailableValues();
        $this->assertEquals($values[0], 'man');
        $this->assertEquals($values[1], 'woman');
        $this->assertEquals($values[2], 'it');

        // first value is null
        $this->assertNull($field->getValue());

        $field->setValue('man');
        $this->assertEquals($field->getValue(), 'man');

        $field->setValue('it');
        $this->assertEquals($field->getValue(), 'it');

        $field->setValue('12345');
        $this->assertNull($field->getValue());

        $field->setValue([1,2,3]);
        $this->assertNull($field->getValue());

        $field->setValue(123456);
        $this->assertNull($field->getValue());

        $field->setValue('woman');
        $this->assertEquals($field->getValue(), 'woman');
    }

    public function testEnumAdvanced()
    {
        $o1 = new stdClass;
        $o2 = (object) [1,2,3];
        $o3 = new IntField(['name' => 'int']);

        $field = new EnumField([
            'name' => 'test field',
            'values' => [
                $o1, $o2, $o3
            ],
        ]);

        $field->setValue($o1);
        $this->assertTrue($field->getValue() === $o1);

        $field->setValue($o2);
        $this->assertTrue($field->getValue() === $o2);

        $field->setValue($o3);
        $this->assertTrue($field->getValue() === $o3);

        // not equivalent

        $field->setValue(new stdClass);
        $this->assertNull($field->getValue());

        $field->setValue((object) [1,2,3]);
        $this->assertNull($field->getValue());

        $field->setValue(new IntField(['name' => 'int']));
        $this->assertNull($field->getValue());
    }
}
