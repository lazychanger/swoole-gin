<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Container\ContainerInterface as PsrContainerInterface;
use SwooleGin\Exception\ContainerNotFoundException;

/**
 * Class Container
 * @package SwooleGin
 *
 */
class Container implements ContainerInterface
{
    protected ?PsrContainerInterface $parent;

    protected array $definitions = [];

    protected array $instances = [];

    protected static self $instance;

    public function __construct(array $definitions = [], PsrContainerInterface $parent = null)
    {
        $this->parent = $parent;

        $this->definitions = $definitions;


        // provider self
        $this->set(ContainerInterface::class, $this);
        $this->set(PsrContainerInterface::class, $this);

        self::$instance = $this;
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, callable|object|string $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->definitions[$id])) {
            $definition = $this->definitions[$id];

            if (is_object($definition)) {
                $this->instances[$id] = $definition;
                return $definition;
            }

            if (is_callable($definition)) {
                $this->instances[$id] = $definition($this);
            } else {
                $this->instances[$id] = new $definition();
            }
            return $this->instances[$id];
        }

        if (!empty($this->parent)) {
            return $this->parent->get($id);
        }

        throw new ContainerNotFoundException($id);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        if (isset($this->instances[$id])) {
            return true;
        }

        if (isset($this->definitions[$id])) {
            return true;
        }

        if (!empty($this->parent)) {
            return $this->parent->has($id);
        }

        return false;
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return self::$instance;
    }
}