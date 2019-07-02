<?php

namespace DigipolisGent\CommandBuilder\Command;

class RawArgument extends Argument
{
    public function __toString()
    {
        return $this->value;
    }
}
