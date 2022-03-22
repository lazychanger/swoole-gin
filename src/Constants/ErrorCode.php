<?php
declare(strict_types=1);


namespace SwooleGin\Constants;


class ErrorCode
{
    // unknown error code
    const UNKNOWN = -1;

    // system error code > 10000 and <= 11000
    const FILE_NOT_EXISTS = 10001;

    // logger exception error code > 11000 and <= 11100
    const UNKNOWN_LOGGER_LEVEL = 11001;
}