<?php

namespace jugger\data\validator;

class RequireValidator implements ValidatorInterface
{
    public function validate($value)
    {
        return ! empty($value);
    }
}
