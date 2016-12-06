<?php

namespace Lib\Logger;

class BlackHoleLogger implements LoggerInterface
{
    public function log($message)
    {
        // NOP
    }
}
