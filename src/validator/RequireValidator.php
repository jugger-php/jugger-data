<?php

namespace jugger\data\validator;

class RequireValidator implements ValidatorInterface
{
    public function validate($value): bool
    {
        return ! is_null($value);
    }
}
