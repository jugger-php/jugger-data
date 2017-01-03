<?php

namespace jugger\data\field;

class BoolField extends BaseField
{
    protected function prepareValue($value)
    {
        return (bool) $value;
    }
}
