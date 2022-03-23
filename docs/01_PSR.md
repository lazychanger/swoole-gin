# PSR

## 介绍

    PSR 是 PHP Standard Recommendations （PHP 推荐标准）的简写，由 PHP FIG 组织制定的 PHP 规范，是 PHP 开发的实践标准。
    
    PHP FIG，FIG 是 Framework Interoperability Group（框架可互用性小组）的缩写，由几位开源框架的开发者成立于 2009 年，从那开始也选取了很多其他成员进来（包括但不限于 Laravel, Joomla, Drupal, Composer, Phalcon, Slim, Symfony, Zend Framework 等），虽然不是「官方」组织，但也代表了大部分的 PHP 社区。

    项目的目的在于：通过框架作者或者框架的代表之间讨论，以最低程度的限制，制定一个协作标准，各个框架遵循统一的编码规范，避免各家自行发展的风格阻碍了 PHP 的发展，解决这个程序设计师由来已久的困扰。

    目前已表决通过了不少套标准，已经得到大部分 PHP 框架的支持和认可。

    本项目的主要面向对象是所有参与的各个成员（也就是各自框架的社区），这里是完整的 成员列表，当然，同时也欢迎其它 PHP 社区采用本规范。

    ————————————————

    原文作者：PHP 技术论坛文档：《PHP PSR 标准规范（）》

    转自链接：https://learnku.com/docs/psr/about-psr/1613

## 现有PSR内容

- [PSR-1 基础编码规范](https://learnku.com/docs/psr/psr-0-automatic-loading-specification/1603)
- [PSR-3 日志接口规范](https://learnku.com/docs/psr/psr-3-logger-interface/1607)
- [PSR-4 自动加载规范](https://learnku.com/docs/psr/psr-4-autoloader/1608)
- [PSR-5 PHPDoc标准](https://learnku.com/docs/psr/psr-5-phpdoc-standard/1611)
- [PSR-6 缓存接口规范](https://learnku.com/docs/psr/psr-6-cache/1614)
- [PSR-7 HTTP消息接口规范](https://learnku.com/docs/psr/psr-7-http-message/1616)
- [PSR-11 容器接口](https://learnku.com/docs/psr/psr-11-container/1621)
- [PSR-12 编码规范补充](https://learnku.com/docs/psr/psr-12-extended-coding-style-guide/5789)
- [PSR-13 超媒体链接](https://learnku.com/docs/psr/psr-13-links/1624)
- [PSR-14 事件分发器](https://learnku.com/docs/psr/psr-14-event-dispatcher/1620)
- [PSR-15 HTTP请求处理器](https://learnku.com/docs/psr/psr-15-request-handlers/1626)
- [PSR-16 缓存接口](https://learnku.com/docs/psr/psr-16-simple-cache/1628)
- [PSR-17 HTTP工厂](https://learnku.com/docs/psr/PSR-17-http-factory/2506)
- [PSR-18 HTTP客户端](https://learnku.com/docs/psr/PSR-18-http-client/2507)

## 本仓库使用规范内容

- PSR-1 基础编码规范
- PSR-3 日志接口规范
- PSR-4 自动加载规范
- PSR-7 HTTP消息接口规范
- PSR-15 HTTP请求处理器
- PSR-18 HTTP客户端

## 为什么使用PSR

PHP是一门弱类型语言，所以官方对其使用方式并没有做过多的约束。

一个项目的开发，前期可能是一个人，等待项目越来越大以后，就可能会有第二个、第三个或者更多的开发者加入进来。 然而不同的人开发习惯也会不同，各自不同的开发风格，就会导致项目后期异常混乱，难以维护。

此时PHP社区的开发者们为了解决该问题，共同商讨提出PSR社区规范，就编码规范、自动加载规范、高度通用组件规范等问题进行处理。

那我们为什么遵循PSR规范呢？我觉得可以有以下理由：

- 统一的编码规范
- 更加通用的组件封装
- 代码模块、组件等之间的解耦
- 更快速的开发

### 使用示例

#### PSR-7

先使用以下命令引入标准库

```shell
composer require psr/http-message
```

其次构建我们自己的`Request`、`Response`对象

```PHP
<?php
declare(strict_types=1);

class Request implements \Psr\Http\Message\RequestInterface
{
    // todo implement \Psr\Http\Message\RequestInterface   
}
```

```PHP
<?php
declare(strict_types=1);

class Request implements \Psr\Http\Message\ResponseInterface
{
    // todo implement \Psr\Http\Message\ResponseInterface   
}
```

然后我们就可以使用我们自己构建的`Request`、`Response`对象去替代任何符合PSR标准HTTP Framework。例如`Hyperf`等。

这个例子可能不太明确。以下例子会更加明确PSR标准库的好处。

我们需要自行开发一个反向代理模块。反向代理实现过程如下：

1. 接受客户端请求
2. 构建回源请求
3. 将回源响应内容返回给客户端

在没有PSR规范以前，我们的实现可能是如下：

```PHP
<?php
declare(strict_types=1);

class Proxy 
{
    protected string $original_host;
    
    protected ClientInterface $client;
    /**
    * @param string $original_host 回源地址
    */
    public function __construct(string $original_host, ClientInterface $client = null) 
    {
        $this->original_host = $original_host;
        $this->client = $client ?? new Client();
    }

    /**
    * 假设只反代GET请求
    * @param string $method eg. get、post、delete
    * @param string $uri
    * @param array $header
    * @return mixed
    */
    public function handle(string $method, string $uri, array $header) 
    {
        return $this->client->$method($uri, ['headers' => $header])->getBody()->getContents();
    }
}

```

以上就可以看出限制的比较死板，如果需要兼容第三方框架，都需要一定量的开发，取出我们所需要的参数；

接下来看一下利用PSR规范写法：

```PHP
<?php
declare(strict_types=1);


namespace SwooleGin\Utils\Proxy;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Proxy
{
    protected string $original_host;
    
    protected ClientInterface $client;
    /**
    * @param string $original_host 回源地址
    */
    public function __construct(string $original_host, ClientInterface $client = null) 
    {
        $this->original_host = $original_host;
        $this->client = $client ?? new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function handler(RequestInterface $request): ResponseInterface
    {
        // 调整回源服务器
        $request->withUri(new Uri($this->options['original']['host'] . $request->getUri()->getPath()), true);

        return $this->client->sendRequest($request);
    }
}
```
我们仅需将`handler`方法的参数调整成`RequestInterface`再利用`PSR-18`HTTP客户端的`ClientInterface`，稍作调整即完成了开发。
并且兼容所有符合`PSR`规范的`客户端`和`请求`。



- [RequestInterface](../src/Request.php) 在本仓库中实现
- [ResponseInterface](../src/Response.php) 在本仓库中实现
- [Proxy](../src/Utils/Proxy)

