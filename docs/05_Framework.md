# 框架

## 基础后端框架结构

- 日志
- 配置
- 路由
- 请求处理器

### 日志

```php
<?php
declare(strict_types=1);


namespace SwooleGin\Logger;


use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Stringable;

class Logger implements LoggerInterface
{
    use LoggerTrait;

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
        // transfer Stringable to string
        $message = strval($message);

        foreach ($context as $value) {
            $message = substr_replace($message, '?', $value);
        }

        echo sprintf("%s-%s: %s \r\n", date('Y-m-d H:i:s'), $level, $message);  
    }
}
```

### 配置

以下是一个简单的配置类:

```php
<?php
declare(strict_types=1);


namespace SwooleGin;


use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class Options
{
    protected ?UriInterface $addr = null;

    /**
     * @var array<string|class-string, object|class-string|callable>
     */

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $value) {
            $setter = sprintf('set%s', ucfirst($key));
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * @return UriInterface|null
     */
    public function getAddr(): ?UriInterface
    {
        if (empty($this->addr)) {
            return new Uri('0.0.0.0:8081');
        }

        return $this->addr;
    }

    /**
     * @param UriInterface|string $addr
     */
    public function setAddr(UriInterface|string $addr): void
    {
        if (is_string($addr)) {
            $addr = new Uri($addr);
        }

        $this->addr = $addr;
    }
}
```

### 请求处理器

```php
<?php

namespace SwooleGin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HandlerInterface
{
    public function ServerHTTP(ResponseInterface $rw, RequestInterface $req): ResponseInterface;
}
```