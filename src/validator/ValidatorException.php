<?php

namespace jugger\data\validator;

class ValidatorException extends \Exception
{
    public $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
