<?php

namespace DigipolisGent\CommandBuilder\Command;

class Option
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
     * Create a new Option.
     *
     * @param string $name
     *   The option name.
     * @param string|null $value
     *   The option value (optional).
     */
    public function __construct($name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __toString()
    {
        return '--' . $this->name . (is_null($this->value) ? '' : '=' . escapeshellarg($this->value));
    }
}
