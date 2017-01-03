<?php

namespace jugger\data\validator;

trait ValidationTrait
{
    protected $_validators;

    public function addValidator(ValidatorInterface $validator)
    {
        $this->_validators[] = $validator;
    }

    public function addValidators(array $validators)
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    public function getValidators()
    {
        return $this->_validators;
    }

    protected function validateValue($value)
    {
        foreach ($this->_validators as $validator) {
            if (!$validator->validate($value)) {
                throw new ValidatorException($validator);
            }
        }
    }
}
