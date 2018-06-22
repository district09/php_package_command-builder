<?php

namespace DigipolisGent\CommandBuilder\Test\Command;

use DigipolisGent\CommandBuilder\Command\Command;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @var string
     */
    protected $commandString;

    /**
     * @var CommandBuilder
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->commandString = uniqid();
        $this->command = new Command($this->commandString);
    }

    public function testAddFlag()
    {
        $flag = uniqid();
        $this->assertEquals($this->command, $this->command->addFlag($flag));
        $this->assertEquals($this->commandString . ' -' . $flag, (string) $this->command);
    }

    public function testAddOption()
    {
        $option = uniqid();
        $this->assertEquals($this->command, $this->command->addOption($option));
        $this->assertEquals($this->commandString . ' --' . $option, (string) $this->command);
    }

    public function testAddOptionWithValue()
    {
        $option = uniqid();
        $value = uniqid();
        $this->assertEquals($this->command, $this->command->addOption($option, $value));
        $this->assertEquals($this->commandString . ' --' . $option . '=' . escapeshellarg($value), (string) $this->command);
    }

    public function testAddArgument()
    {
        $argument = uniqid();
        $this->assertEquals($this->command, $this->command->addArgument($argument));
        $this->assertEquals($this->commandString . ' ' . escapeshellarg($argument), (string) $this->command);
    }
}
