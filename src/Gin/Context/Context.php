<?php
declare(strict_types=1);


namespace SwooleGin\Gin\Context;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SwooleGin\ContainerInterface;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPMime;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;


class Context
{
    private int $index = -1;
    private int $abort_index = 0;

    private array $handlers = [];

    protected bool $parsedQuery = false;
    protected array $query = [];

    protected bool $parsedBody = false;
    protected array $body = [];


    /**
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @param ContainerInterface $container
     * @param array<array-key, ContextHandlerFuncInterface> $handlers
     */
    public function __construct(
        public ResponseInterface $response,
        public RequestInterface $request,
        public ContainerInterface $container,
        array $handlers = []
    ) {
        if (empty($handlers)) {
            throw new RuntimeException('Missing Handler');
        }
        $this->handlers = $handlers;
        $this->abort_index = count($handlers) - 1;

        self::$_container = $this->container;
    }

    public function JSON(int $code, array $data = [])
    {
        $this->response->withStatus($code);
        $this->response->withBody(new StringStream((new JsonEncode())->encode($data, 'json')));
        $this->response->withHeader('Content-Type', HTTPMime::APPLICATION_JSON);
    }

    public function Raw(int $code, string $data)
    {
        $this->response->withStatus($code);
        $this->response->withBody(new StringStream($data));
        $this->response->withHeader('Content-Type', HTTPMime::TEXT_PLAIN);
    }

    public function XML(int $code, array $data)
    {
        $this->response->withStatus($code);
        $this->response->withBody(new StringStream((new XmlEncoder())->encode($data, XmlEncoder::FORMAT)));
        $this->response->withHeader('Content-Type', HTTPMime::TEXT_PLAIN);
    }

    public function abort()
    {
        $this->index = $this->abort_index;
    }

    public function next()
    {
        ++$this->index;
        while ($this->index <= $this->abort_index) {
            $this->handlers[$this->index]($this);
            ++$this->index;
        }
    }

    public function query(string $key, string $value = ''): string
    {
        if (!$this->parsedQuery) {
            parse_str($this->request->getUri()->getQuery(), $this->query);
        }

        return !empty($this->query[$key]) ? $this->query[$key] : $value;
    }


    public function post(string $key, $value = null)
    {
        if (!$this->parsedBody) {
            $this->body = json_decode($this->request->getBody()->getContents(), true);
        }
        return !empty($this->body[$key]) ? $this->body[$key] : $value;
    }

    protected static ?\Psr\Container\ContainerInterface $_container;

    protected static function getContainer(): \Psr\Container\ContainerInterface
    {
        return self::$_container;
    }
}