<?php

namespace DigipolisGent\CommandBuilder\Command;

class Command
{

    /**
     * @var string
     */
    protected $command;

    /**
     * @var Flag[]
     */
    protected $flags;

    /**
     * @var Option[]
     */
    protected $options;

    /**
     * @var Argument[]
     */
    protected $arguments;

    /**
     * Create a command.
     *
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = (string)$command;
        $this->flags = [];
        $this->options = [];
        $this->arguments = [];
    }

    /**
     * Add a flag to the command.
     *
     * @param string $flag
     *   The flag to add, without the leading dash.
     *
     * @return Command
     */
    public function addFlag($flag): Command
    {
        $this->flags[] = new Flag($flag);

        return $this;
    }

    /**
     * Add an option to the command.
     *
     * @param string $option
     *   The option to add, without the leading dashes.
     * @param string $value
     *   The value for the option (optional).
     *
     * @return Command
     */
    public function addOption($option, $value = null): Command
    {
        $this->options[] = new Option($option, $value);

        return $this;
    }

    /**
     * Add an argument to the command.
     *
     * @param string $argument
     *   The argument to add.
     *
     * @return Command
     */
    public function addArgument($argument): Command
    {
        $this->arguments[] = new Argument($argument);

        return $this;
    }

    public function __toString()
    {
        $command = $this->command;
        if ($this->flags) {
            $command .= ' ' . implode(' ', $this->flags);
        }
        if ($this->options) {
            $command .= ' ' . implode(' ', $this->options);
        }
        if ($this->arguments) {
            $command .= ' ' . implode(' ', $this->arguments);
        }
        return $command;
    }
}
