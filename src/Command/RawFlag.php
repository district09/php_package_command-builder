<?php

namespace DigipolisGent\CommandBuilder\Command;

class RawFlag extends Flag
{
    public function __toString()
    {
        return '-' . $this->name . (is_null($this->value) ? '' : ' ' . $this->value);
    }
}
