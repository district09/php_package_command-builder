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

    protected function setUp(): void
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

    public function testAddRawFlag()
    {
        $flag = uniqid();
        $this->assertEquals($this->command, $this->command->addRawFlag($flag));
        $this->assertEquals($this->commandString . ' -' . $flag, (string) $this->command);
    }

    public function testAddFlagWithValue()
    {
        $flag = uniqid();
        $value = uniqid();
        $this->assertEquals($this->command, $this->command->addFlag($flag, $value));
        $this->assertEquals($this->commandString . ' -' . $flag . ' ' . escapeshellarg($value), (string) $this->command);
    }

    public function testAddRawFlagWithValue()
    {
        $flag = uniqid();
        $value = uniqid();
        $this->assertEquals($this->command, $this->command->addRawFlag($flag, $value));
        $this->assertEquals($this->commandString . ' -' . $flag . ' ' . $value, (string) $this->command);
    }

    public function testAddOption()
    {
        $option = uniqid();
        $this->assertEquals($this->command, $this->command->addOption($option));
        $this->assertEquals($this->commandString . ' --' . $option, (string) $this->command);
    }

    public function testAddRawOption()
    {
        $option = uniqid();
        $this->assertEquals($this->command, $this->command->addRawOption($option));
        $this->assertEquals($this->commandString . ' --' . $option, (string) $this->command);
    }

    public function testAddOptionWithValue()
    {
        $option = uniqid();
        $value = uniqid();
        $this->assertEquals($this->command, $this->command->addOption($option, $value));
        $this->assertEquals($this->commandString . ' --' . $option . '=' . escapeshellarg($value), (string) $this->command);
    }

    public function testAddRawOptionWithValue()
    {
        $option = uniqid();
        $value = uniqid();
        $this->assertEquals($this->command, $this->command->addRawOption($option, $value));
        $this->assertEquals($this->commandString . ' --' . $option . '=' . $value, (string) $this->command);
    }

    public function testAddArgument()
    {
        $argument = uniqid();
        $this->assertEquals($this->command, $this->command->addArgument($argument));
        $this->assertEquals($this->commandString . ' ' . escapeshellarg($argument), (string) $this->command);
    }

    public function testAddRawArgument()
    {
        $argument = uniqid();
        $this->assertEquals($this->command, $this->command->addRawArgument($argument));
        $this->assertEquals($this->commandString . ' ' . $argument, (string) $this->command);
    }
}
