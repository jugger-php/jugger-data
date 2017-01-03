<?php

namespace jugger\data\validator;

class ValidatorException extends \Exception
{
    public $validator;

    public function __construct(VaildatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
