<?php

namespace jugger\data\validator;

class CompareValidator implements ValidatorInterface
{
    protected $operator;
    protected $compareValue;

    public function __construct(string $operator, $compareValue)
    {
        $this->operator = $operator;
        $this->compareValue = $compareValue;
    }

    public function validate($a)
    {
        $b = $this->compareValue;
        switch ($this->operator) {
            case '==':
                return $a == $b;
            case '===':
                return $a == $b;
            case '!=':
                return $a != $b;
            case '!==':
                return $a !== $b;
            case '>':
                return $a > $b;
            case '>=':
                return $a >= $b;
            case '<':
                return $a < $b;
            case '<=':
                return $a <= $b;
            case '<=>':
                return $a <=> $b;
            default:
                throw new \Exception("Invalide operator is '{$this->operator}'");
        }
    }
}
