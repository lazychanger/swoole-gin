<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Log\LoggerAwareTrait;
use SwooleGin\Logger\Logger;

use Swoole\Server as SwooleServer;
use SwooleGin\Stream\StringStream;
use Throwable;

class Server
{
    const MAIN_STATE = 'main';

    use LoggerAwareTrait;

    protected SwooleServer $server;

    protected ?State $state;

    protected HandlerInterface $handler;

    protected ContainerInterface $container;

    public function __construct(protected Options $options)
    {
        $this->setLogger(new Logger(Logger::LEVEL_DEBUG));

        if ($this->options->getState()) {
            $this->state = new State();
        }

        $this->container = new Container($this->options->getDefinitions(), $this->options->getContainer());
    }


    public function serve()
    {
        $this->server = new SwooleServer($this->options->getAddr()->getHost(), $this->options->getAddr()->getPort());

        $this->server->on('connect', function (SwooleServer $server, int $fd, int $reactor_id) {
            !!$this->state && $this->state->setfd($fd, [State::FIELD_CTIME => time(), State::FIELD_FD => $fd]);
        });


        $this->server->on('Receive', function (SwooleServer $server, int $fd, int $reactor_id, string $data) {
            $response = new Response();
            try {
                $request = Request::parseRequest($data);

                if (!empty($this->handler)) {
                    $response = $this->handler->ServerHTTP($response, $request);
                } else {
                    $response->withStatus(404, 'Not Found');
                    $response->withBody(new StringStream('Not Found'));
                }

                ResponseWriter::write($server, $fd, $response, $this->options->getBufferSize());
            } catch (Throwable $exception) {
                $response->withStatus($exception->getCode());
                $response->withBody(new StringStream($exception->getMessage()));

                $this->logger->error($exception->getMessage());
                $this->logger->error($exception->getTraceAsString());

                ResponseWriter::write($server, $fd, $response, $this->options->getBufferSize());
            }

            $server->close($fd);
        });


        $this->server->on('Close', function (SwooleServer $server, int $fd, int $reactorId) {
            !!$this->state && $this->state->delfd($fd);
        });

        $this->logger->info(sprintf('server has been listen on %s:%d', $this->options->getAddr()->getHost(),
            $this->options->getAddr()->getPort()));
        $this->server->start();
    }

    public function state(): array
    {
        $running_time = time() - $this->state->get(self::MAIN_STATE, State::FIELD_CTIME);
        $d = floor($running_time / 86400);
        $h = floor(($running_time % 86400) / 3600);
        return [
            sprintf(
                'server running. health: %dd%dh%dm%ss',
                $d,
                $h,
                floor(($running_time - $d * 86400 - $h * 3600) / 60),
                floor($running_time % 60),
            ),
            sprintf('client: %d', $this->state->count())
        ];
    }

    /**
     * @return HandlerInterface
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param HandlerInterface $handler
     */
    public function setHandler(HandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    public function shutdown()
    {
        $this->server->shutdown();
    }
}