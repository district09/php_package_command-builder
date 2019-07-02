<?php

namespace DigipolisGent\CommandBuilder\Command;

class RawOption extends Option
{
    public function __toString()
    {
        return '--' . $this->name . (is_null($this->value) ? '' : '=' . $this->value);
    }
}
