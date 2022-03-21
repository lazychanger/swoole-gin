<?php
declare(strict_types=1);


namespace SwooleGin\Logger;


class StdoutLogger extends Logger
{
    public function __construct(int $log_level = self::LEVEL_INFO)
    {
        parent::__construct($log_level, self::STDOUT);
    }
}