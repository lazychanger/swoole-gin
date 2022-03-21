<?php
declare(strict_types=1);


namespace SwooleGin;


use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class Options
{
    protected bool $state = true;

    protected ?UriInterface $addr = null;

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