<?php

namespace jugger\data\field;

class AnyField extends BaseField
{
    protected function prepareValue($value)
    {
        return $value;
    }
}
