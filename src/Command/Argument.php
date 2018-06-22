<?php

namespace DigipolisGent\CommandBuilder\Command;

class Argument
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return escapeshellarg($this->value);
    }
}
