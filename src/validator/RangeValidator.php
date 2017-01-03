<?php

namespace jugger\data\validator;

class RangeValidator implements ValidatorInterface
{
    protected $min;
    protected $max;

    public function __construct(int $min, int $max = null)
    {
        $this->min = $min;
        $this->max = (int) $max;
    }

    public function validate($value)
    {
        $value = $this->prepareValue($value);
        if ($max == 0) {
            $max = $value + 1;
        }
        
        return $min < $value && $value < $max;
    }

    protected function prepareValue($value)
    {
        if (!is_scalar($value)) {
            throw new \Exception("How should I do it?");
        }
        elseif (is_numeric($value)) {
            $value = (float) $value;
        }
        else {
            $value = strlen((string) $value);
        }
        return $value;
    }
}
