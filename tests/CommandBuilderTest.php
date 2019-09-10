<?php

namespace DigipolisGent\CommandBuilder\Test;

use DigipolisGent\CommandBuilder\Command\Command;
use DigipolisGent\CommandBuilder\CommandBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandBuilderTest extends TestCase
{
    /**
     * @var MockObject|Command
     */
    protected $commandMock;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var CommandBuilder
     */
    protected $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->command = uniqid();
        $this->commandMock = $this->prophesize(Command::class)
            ->willBeConstructedWith([$this->command]);
        $this->builder = CommandBuilder::create(uniqid());
    }
    public function testCreate()
    {
        $execute = uniqid();
        $command = CommandBuilder::create($execute);
        $this->assertInstanceOf(CommandBuilder::class, $command);
        $this->assertEquals($execute, $command->getCommand());
    }

    public function testAddFlag()
    {
        $flag = uniqid();
        $this->commandMock
            ->addFlag($flag, null)
            ->shouldBeCalledOnce();
        $this->setCommand($this->commandMock->reveal());
        $this->assertEquals($this->builder, $this->builder->addFlag($flag));
    }

    public function testAddOption()
    {
        $option = uniqid();
        $this->commandMock
            ->addOption($option, null)
            ->shouldBeCalledOnce();
        $this->setCommand($this->commandMock->reveal());
        $this->assertEquals($this->builder, $this->builder->addOption($option));
    }

    public function testAddOptionWithValue()
    {
        $option = uniqid();
        $value = uniqid();
        $this->commandMock
            ->addOption($option, $value)
            ->shouldBeCalledOnce();
        $this->setCommand($this->commandMock->reveal());
        $this->assertEquals($this->builder, $this->builder->addOption($option, $value));
    }

    public function testAddArgument()
    {
        $argument = uniqid();
        $this->commandMock
            ->addArgument($argument)
            ->shouldBeCalledOnce();
        $this->setCommand($this->commandMock->reveal());
        $this->assertEquals($this->builder, $this->builder->addArgument($argument));
    }

    /**
     * @dataProvider chainingTestDataProvider
     */
    public function testChaining($method, $operator)
    {
        $this->commandMock->__toString()->willReturn($this->command);
        $this->setCommand($this->commandMock->reveal());
        $chained = uniqid();
        $this->assertEquals($this->builder, $this->builder->{$method}($chained));
        $this->assertEquals('{ ' . $this->command . ' ' . $operator . ' ' . $chained . '; }', (string) $this->builder);
    }

    /**
     * @dataProvider chainingTestDataProvider
     */
    public function testChainingWithDifferentOperator($method, $operator, $differentMethod, $differentOperator)
    {
        $this->commandMock->__toString()->willReturn($this->command);
        $this->setCommand($this->commandMock->reveal());
        $chained = uniqid();
        $chained2 = uniqid();
        $this->assertEquals($this->builder, $this->builder->{$method}($chained));
        $this->assertEquals($this->builder, $this->builder->{$differentMethod}($chained2));
        $this->assertEquals('{ { ' . $this->command . ' ' . $operator . ' ' . $chained . '; } ' . $differentOperator . ' ' . $chained2 . '; }', (string) $this->builder);
    }

    public function chainingTestDataProvider()
    {
        return [
            ['onSuccess', '&&', 'onFailure', '||'],
            ['onFailure', '||', 'onFinished', ';'],
            ['onFinished', ';', 'pipeOutputTo', '|'],
            ['pipeOutputTo', '|', 'onSuccess', '&&'],
        ];
    }

    protected function setCommand(Command $command)
    {
        $currentCommand = new \ReflectionProperty($this->builder, 'currentCommand');
        $currentCommand->setAccessible(true);
        $currentCommand->setValue($this->builder, $command);
        $chain = new \ReflectionProperty($this->builder, 'chain');
        $chain->setAccessible(true);
        $chain->setValue($this->builder, [$command]);
    }
}
