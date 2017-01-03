<?php

use PHPUnit\Framework\TestCase;

use jugger\data\field\AnyField;
use jugger\data\validator\EmailValidator;
use jugger\data\validator\RangeValidator;
use jugger\data\validator\RegexpValidator;
use jugger\data\validator\CompareValidator;
use jugger\data\validator\RequireValidator;

class ValidatorTest extends TestCase
{
    public function testBase()
    {
        $field = new AnyField([
            'name' => 'test',
            'value' => 'default',
        ]);

        $field->addValidator(new RequireValidator());
        $field->addValidator(new EmailValidator());

        $field->addValidator(new RangeValidator(10));
        $field->addValidator(new RangeValidator(0,50));
        $field->addValidator(new RangeValidator(10,50));

        $field->addValidator(new CompareValidator('>', 10));
        $field->addValidator(new RegexpValidator("/pattern/"));

        $validators = $field->getValidators();
        $this->assertEquals(count($validators), 7);
    }
}
