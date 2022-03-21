<?php
declare(strict_types=1);


namespace SwooleGin\Logger;


use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Stringable;

class Logger implements LoggerInterface
{
    const LEVEL_DEBUG = -1;
    const LEVEL_INFO = 0;
    const LEVEL_NOTICE = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;
    const LEVEL_CRITICAL = 4;
    const LEVEL_ALERT = 5;
    const LEVEL_EMERGENCY = 6;

    use LoggerTrait;

    const STDOUT = '/dev/stdout';
    const STDERR = '/dev/stderr';

    private $fd;

    protected array $log_level_map = [
        LogLevel::DEBUG => -1,
        LogLevel::INFO => 0,
        LogLevel::NOTICE => 1,
        LogLevel::WARNING => 2,
        LogLevel::ERROR => 3,
        LogLevel::CRITICAL => 4,
        LogLevel::ALERT => 5,
        LogLevel::EMERGENCY => 6,
    ];

    public function __construct(protected int $log_level = self::LEVEL_INFO, string $output = '')
    {
        if (!empty($output)) {
            $this->fd = fopen($output, 'a');
        }
    }

    /**
     * $logger->log('warning', 'hello ?', ['world'])
     *
     * @param string $level
     * @param Stringable|string $message
     * @param array $context
     * @return void
     * @throws UnknownLevelException
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->log_level_map[$level] ?? throw new UnknownLevelException();

        if ($this->log_level_map[$level] < $this->log_level) {
            return;
        }

        $message = strval($message);

        foreach ($context as $value) {
            $message = substr_replace($message, '?', $value);
        }

        $row = sprintf("%s-%s: %s \r\n", date('Y-m-d H:i:s'), $level, $message);
        echo $row;


        // 写入文件
        if (!empty($this->fd)) {
            fwrite($this->fd, $row);
        }

    }


    public function __destruct()
    {
        if (!empty($this->fd)) {
            fclose($this->fd);
        }
    }

}