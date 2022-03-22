# swoole-gin
教学仓库

使用PHP基于Swoole TCP完成 golang gin framework 风格的HTTP框架
实现Restful风格路由、洋葱结构中间间，上下文封装

## 使用方法
```php
declare(strict_types=1);


use SwooleGin\Gin\Context\Context;
use SwooleGin\Gin\Context\ContextHandlerFuncInterface;
use SwooleGin\Gin\Gin;
use SwooleGin\Gin\Middleware\FaviconMiddleware;
use SwooleGin\Options;
use SwooleGin\Server;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPStatus;

include_once __DIR__ . '/../vendor/autoload.php';

$servOpts = new Options();
$servOpts->setAddr('0.0.0.0:8082');

$serv = new Server($servOpts);

$engine = new Gin();
$engine->use(
    (new FaviconMiddleware),
    // 假鉴权中间件
    (new class implements ContextHandlerFuncInterface {
        public function __invoke(Context $context)
        {
            if ($context->query('token') !== '123456') {
                $context->response->withBody(new StringStream('authorized failed'));
                $context->response->withStatus(HTTPStatus::StatusForbidden);
                $context->abort();
            }
        }
    }),
    // 用于模拟洋葱结构中间件：打印响应内容
    (new class implements ContextHandlerFuncInterface {
        public function __invoke(Context $context)
        {
            $context->next();

            $body = $context->response->getBody()->getContents();
            echo 'resp:', $body, PHP_EOL;
            $context->response->withBody(new StringStream($body));
        }
    }),
);
$engine->GET('/hello', (new class implements ContextHandlerFuncInterface {
    public function __invoke(Context $context)
    {
        $context->Raw(HTTPStatus::StatusOK, 'hello world');
    }
}));

$engine->setOnNotFound((new class implements ContextHandlerFuncInterface {
    public function __invoke(Context $context)
    {
        $context->JSON(HTTPStatus::StatusOK, ['code' => HTTPStatus::StatusNotFound, 'msg' => 'not found']);
    }

}));

$serv->setHandler($engine);
// 服务启动
$serv->serve();

```


## todo

- [x] middleware
- [x] context
- [ ] gin-router