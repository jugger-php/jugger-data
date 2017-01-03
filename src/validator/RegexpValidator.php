<?php

namespace jugger\data\validator;

class RegexpValidator implements ValidatorInterface
{
    protected $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function validate($value)
    {
        return preg_match($this->pattern, $value) !== false;
    }
}
