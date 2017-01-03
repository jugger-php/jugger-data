<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Model;
use jugger\data\field\IntField;
use jugger\data\field\TextField;
use jugger\data\field\EnumField;
use jugger\data\field\BoolField;

class People extends Model
{
    public static function getSchema()
    {
        return [
            new IntField([
                'name' => 'age',
            ]),
            new TextField([
                'name' => 'fio',
            ]),
            new EnumField([
                'name' => 'sex',
                'values' => [
                    'man', 'woman'
                ],
            ]),
            new BoolField([
                'name' => 'is_superman',
            ]),
        ];
    }
}

class ModelTest extends TestCase
{
    public function testBase()
    {
        $people = new People();
        $people->age = 27;
        $people->fio = 'Ilya R';
        $people->sex = 'man';
        $people->is_superman = true;

        $this->assertEquals($people->age, 27);
        $this->assertEquals($people->fio, 'Ilya R');
        $this->assertEquals($people->sex, 'man');
        $this->assertTrue($people->is_superman);

        // not exists
        $this->assertTrue($people->existsField('age'));
        $this->assertTrue($people->existsField('fio'));
        $this->assertFalse($people->existsField('404 field'));

        // array access
        $this->assertEquals($people->age, $people['age']);
        $this->assertEquals($people->fio, $people['fio']);
        $this->assertEquals($people->sex, $people['sex']);

        $this->assertTrue(isset($people['age']));
        $this->assertTrue(isset($people['fio']));
        $this->assertFalse(isset($people['404 field 2']));

        $age = $people['age'];
        unset($people['age']);
        $this->assertNull($people['age']);
        $people['age'] = $age;

        return $people;
    }

    /**
     * @depends testBase
     */
    public function testValues(Model $people)
    {
        $values = $people->getValues();

        $this->assertEquals($values['age'], 27);
        $this->assertEquals($values['fio'], 'Ilya R');
        $this->assertEquals($values['sex'], 'man');
        $this->assertTrue($values['is_superman']);

        $values['age'] = 123;
        $people->setValues($values);
        $this->assertEquals($people['age'], 123);

        // dirty write

        $values = [
            'age'   => '456',
            'key'   => 'value',
            1234    => new stdClass,
        ];

        $people->setValues($values);
        $this->assertEquals($people['age'], 456);
        $this->assertFalse(isset($people['key']));
        $this->assertFalse(isset($people[1234]));
    }
}
