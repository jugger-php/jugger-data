<?php

namespace jugger\data\field;

use jugger\data\validator\ValidationTrait;

abstract class BaseField
{
    use ValidationTrait;

    protected $_name;
    protected $_value;

    public function __construct(array $config = [])
    {
        // set name
        $name = $config['name'] ?? null;
        if (!is_string($name)) {
            throw new \Exception("Property 'name' is required");
        }
        else {
            $this->_name = $name;
            unset($config['name']);
        }
        // set default
        $value = $config['value'] ?? null;
        if ($value) {
            $this->setValue($value);
            unset($config['value']);
        }
        // init
        $this->init($config);
    }

    public function init(array $config)
    {
        // pass
    }

    public function setValue($value)
    {
        if (is_null($value)) {
            $this->_value = null;
        }
        else {
            $this->_value = $this->prepareValue($value);
        }
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function validate()
    {
        return $this->validateValue($this->getValue());
    }

    abstract protected function prepareValue($value);
}
