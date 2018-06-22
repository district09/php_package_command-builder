<?php

namespace DigipolisGent\CommandBuilder\Command;

class Flag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Create a new flag.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return '-' . $this->name;
    }
}
