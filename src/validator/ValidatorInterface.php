<?php

namespace jugger\data\validator;

interface ValidatorInterface
{
    public function validate($value): bool;
}
