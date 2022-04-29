<?php
declare(strict_types=1);


namespace SwooleGin;


use GuzzleHttp\Psr7\Uri;
use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\UriInterface;

class Options
{
    protected int $buffer_size = 4096;

    protected bool $state = true;

    protected ?UriInterface $addr = null;

    /**
     * @var array<string|class-string, object|class-string|callable>
     */
    protected array $definitions = [];

    protected ?PsrContainerInterface $container = null;

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
     * @return bool
     */
    public function getState(): bool
    {
        return $this->state;
    }

    /**
     * @param bool $state
     */
    public function setState(bool $state): void
    {
        $this->state = $state;
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

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array<string|class-string, object|class-string|callable> $definitions
     */
    public function setDefinitions(array $definitions): void
    {
        $this->definitions = $definitions;
    }

    /**
     * @return PsrContainerInterface|null
     */
    public function getContainer(): ?PsrContainerInterface
    {
        return $this->container;
    }

    /**
     * @param PsrContainerInterface $container
     */
    public function setContainer(PsrContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @return int
     */
    public function getBufferSize(): int
    {
        return $this->buffer_size;
    }

    /**
     * @param int $buffer_size
     */
    public function setBufferSize(int $buffer_size): void
    {
        $this->buffer_size = $buffer_size;
    }

}