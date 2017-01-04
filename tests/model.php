<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Model;
use jugger\data\field\IntField;
use jugger\data\field\TextField;
use jugger\data\field\EnumField;
use jugger\data\field\BoolField;
use jugger\data\validator\RangeValidator;
use jugger\data\validator\RequireValidator;
use jugger\data\validator\DynamicValidator;

class People extends Model
{
    public static function getSchema()
    {
        return [
            new IntField([
                'name' => 'age',
                'validators' => [
                    // возвраст в диапазоне 3-150
                    new RangeValidator(3, 150)
                ],
            ]),
            new TextField([
                'name' => 'fio',
                'validators' => [
                    // ФИО с диапазоне 1-15 символов
                    new RangeValidator(1, 15)
                ],
            ]),
            new EnumField([
                'name' => 'sex',
                'values' => [
                    'man', 'woman'
                ],
                'validators' => [
                    // обязательно с выбранным полом
                    new RequireValidator()
                ],
            ]),
            new BoolField([
                'name' => 'is_superman',
                'validators' => [
                    // только супермены
                    new DynamicValidator(function(bool $value) {
                        return $value === true;
                    })
                ],
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

    public function testValidators()
    {
        $superman = new People([
            'age' => 78,
            'fio' => 'Кларк Кент',
            'sex' => 'man',
            'is_superman' => true,
        ]);
        $this->assertTrue($superman->validate());
        $this->assertTrue(count($superman->getErrors()) == 0);

        $superman->age = 666;
        $this->assertFalse($superman->validate());
        $this->assertEquals($superman->getError('age'), RangeValidator::class);

        $superman->fio = 'Кларк Джозеф Кент';
        $this->assertFalse($superman->validate());
        $this->assertEquals($superman->getError('fio'), RangeValidator::class);

        $superman->sex = null;
        $this->assertFalse($superman->validate());
        $this->assertEquals($superman->getError('sex'), RequireValidator::class);

        $superman->is_superman = false;
        $this->assertFalse($superman->validate());
        $this->assertEquals($superman->getError('is_superman'), DynamicValidator::class);

        $errors = $superman->getErrors();
        $this->assertEquals($errors['age'], RangeValidator::class);
        $this->assertEquals($errors['fio'], RangeValidator::class);
        $this->assertEquals($errors['sex'], RequireValidator::class);
        $this->assertEquals($errors['is_superman'], DynamicValidator::class);
    }
}
