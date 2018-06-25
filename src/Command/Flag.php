<?php

namespace DigipolisGent\CommandBuilder\Command;

class Flag
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * Create a new Flag.
     *
     * @param string $name
     */
    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __toString()
    {
        return '-' . $this->name . (is_null($this->value) ? '' : ' ' . escapeshellarg($this->value));
    }
}
