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

    protected function setUp()
    {
        parent::setUp();
        $this->command = uniqid();
        $this->commandMock = $this->getMockBuilder(Command::class)
            ->setConstructorArgs([$this->command])
            ->setMethods([
                'addFlag',
                'addOption',
                'addArgument',
            ])
            ->getMock();
        $this->builder = CommandBuilder::create(uniqid());
        $currentCommand = new \ReflectionProperty($this->builder, 'currentCommand');
        $currentCommand->setAccessible(true);
        $currentCommand->setValue($this->builder, $this->commandMock);
        $chain = new \ReflectionProperty($this->builder, 'chain');
        $chain->setAccessible(true);
        $chain->setValue($this->builder, [$this->commandMock]);
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
            ->expects($this->once())
            ->method('addFlag')
            ->with($flag)
            ->willReturnSelf();
        $this->assertEquals($this->builder, $this->builder->addFlag($flag));
    }

    public function testAddOption()
    {
        $option = uniqid();
        $this->commandMock
            ->expects($this->once())
            ->method('addOption')
            ->with($option)
            ->willReturnSelf();
        $this->assertEquals($this->builder, $this->builder->addOption($option));
    }

    public function testAddOptionWithValue()
    {
        $option = uniqid();
        $value = uniqid();
        $this->commandMock
            ->expects($this->once())
            ->method('addOption')
            ->with($option, $value)
            ->willReturnSelf();
        $this->assertEquals($this->builder, $this->builder->addOption($option, $value));
    }

    public function testAddArgument()
    {
        $argument = uniqid();
        $this->commandMock
            ->expects($this->once())
            ->method('addArgument')
            ->with($argument)
            ->willReturnSelf();
        $this->assertEquals($this->builder, $this->builder->addArgument($argument));
    }

    /**
     * @dataProvider chainingTestDataProvider
     */
    public function testChaining($method, $operator)
    {
        $chained = uniqid();
        $this->assertEquals($this->builder, $this->builder->{$method}($chained));
        $chainProperty = new \ReflectionProperty(CommandBuilder::class, 'chain');
        $chainProperty->setAccessible(true);
        $this->assertEquals('{ ' . $this->command . ' ' . $operator . ' ' . $chained . '; }', (string) $this->builder);
    }

    /**
     * @dataProvider chainingTestDataProvider
     */
    public function testChainingWithDifferentOperator($method, $operator, $differentMethod, $differentOperator)
    {
        $chained = uniqid();
        $chained2 = uniqid();
        $this->assertEquals($this->builder, $this->builder->{$method}($chained));
        $this->assertEquals($this->builder, $this->builder->{$differentMethod}($chained2));
        $chainProperty = new \ReflectionProperty(CommandBuilder::class, 'chain');
        $chainProperty->setAccessible(true);
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

}
