<?php

namespace DigipolisGent\CommandBuilder;

use DigipolisGent\CommandBuilder\Command\Command;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CommandBuilder
{

    /**
     * @var Command[]
     */
    protected $chain;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var Command
     */
    protected $currentCommand;

    /**
     * Creates a CommandBuilder.
     *
     * @param string|CommandBuilder $command
     *   The command to create the builder for.
     */
    public function __construct($command)
    {
        $this->operator = null;
        $this->currentCommand = new Command($command);
        $this->chain = [$this->currentCommand];
    }

    /**
     * Static constructor
     *
     * @param string|CommandBuilder $command
     *   The command to create the builder for.
     *
     * @return CommandBuilder
     */
    public static function create($command)
    {
        return new CommandBuilder($command);
    }

    /**
     * Add a flag to the command.
     *
     * @param string $flag
     *   The flag to add, without the leading dash.
     * @param string $value
     *   The value for the flag (optional).
     *
     * @return CommandBuilder
     */
    public function addFlag($flag, $value = null): CommandBuilder
    {
        $this->currentCommand->addFlag($flag, $value);

        return $this;
    }

    /**
     * Add a raw flag to the command.
     *
     * @param string $flag
     *   The flag to add, without the leading dash.
     * @param string $value
     *   The value for the flag (optional).
     *
     * @return CommandBuilder
     */
    public function addRawFlag($flag, $value = null): CommandBuilder
    {
        $this->currentCommand->addRawFlag($flag, $value);

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
     * @return CommandBuilder
     */
    public function addOption($option, $value = null): CommandBuilder
    {
        $this->currentCommand->addOption($option, $value);

        return $this;
    }

    /**
     * Add a raw option to the command.
     *
     * @param string $option
     *   The option to add, without the leading dashes.
     * @param string $value
     *   The value for the option (optional).
     *
     * @return CommandBuilder
     */
    public function addRawOption($option, $value = null): CommandBuilder
    {
        $this->currentCommand->addRawOption($option, $value);

        return $this;
    }

    /**
     * Add an argument to the command.
     *
     * @param string $argument
     *   The argument to add.
     *
     * @return CommandBuilder
     */
    public function addArgument($argument): CommandBuilder
    {
        $this->currentCommand->addArgument($argument);

        return $this;
    }

    /**
     * Add a raw argument to the command.
     *
     * @param string $argument
     *   The argument to add.
     *
     * @return CommandBuilder
     */
    public function addRawArgument($argument): CommandBuilder
    {
        $this->currentCommand->addRawArgument($argument);

        return $this;
    }

    /**
     * Add a command to execute on success.
     *
     * @param string|CommandBuilder $command
     *
     * @return CommandBuilder
     */
    public function onSuccess($command): CommandBuilder
    {
        return $this->chain($command, '&&');
    }

    /**
     * Add a command to execute on failure.
     *
     * @param string|CommandBuilder $command
     *
     * @return CommandBuilder
     */
    public function onFailure($command): CommandBuilder
    {
        return $this->chain($command, '||');
    }

    /**
     * Add a command to execute after the command, regardless of exit status.
     *
     * @param string|CommandBuilder $command
     *
     * @return CommandBuilder
     */
    public function onFinished($command): CommandBuilder
    {
        return $this->chain($command, ';');
    }

    /**
     * Add a command to pipe the output to.
     *
     * @param string|CommandBuilder $command
     *
     * @return CommandBuilder
     */
    public function pipeOutputTo($command): CommandBuilder
    {
        return $this->chain($command, '|');
    }

    /**
     * Get the command as a string.
     *
     * @return string
     */
    public function getCommand(): string
    {
        if (count($this->chain) === 1) {
            return (string) reset($this->chain);
        }
        return '{ ' . implode(' ' . $this->operator . ' ', $this->chain) . '; }';
    }

    public function __toString()
    {
        return $this->getCommand();
    }

    /**
     * Chain commands together.
     *
     * @param string|CommandBuilder $command
     *   The command to chain.
     * @param string $operator
     *   The operator to chain with.
     *
     * @return CommandBuilder
     */
    public function chain($command, $operator): CommandBuilder
    {
        if (is_null($this->operator)) {
            $this->operator = $operator;
        }
        if ($this->operator != $operator) {
            $this->chain = [clone $this];
            $this->operator = $operator;
        }

        $this->currentCommand = new Command($command);
        $this->chain[] = $this->currentCommand;

        return $this;
    }

    public function __clone()
    {
        $newChain = [];
        foreach ($this->chain as $command) {
            if (is_object($command)) {
                $newChain[] = clone $command;
                continue;
            }
            $newChain[] = $command;
        }
        $this->chain = $newChain;
        $this->currentCommand = end($this->chain);
    }
}
