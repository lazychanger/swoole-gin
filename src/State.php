<?php
declare(strict_types=1);


namespace SwooleGin;


use Swoole\Table;

final class State
{
    const FIELD_FD = 'fd';
    // created time
    const FIELD_CTIME = 'ctime';


    protected Table $state;

    public function __construct(int $table_size = 4096)
    {

        $state = new Table($table_size);
        $state->column(self::FIELD_FD, Table::TYPE_INT);
        $state->column(self::FIELD_CTIME, Table::TYPE_INT);
        $state->create();
        $this->state = $state;
    }

    public function setfd(int $fd, array $value): void
    {
        $this->state->set($this->build_key($fd), $value);
    }

    public function getfd(int $fd, string $field = null): mixed
    {
        return $this->state->get($this->build_key($fd), $field);
    }

    public function delfd(int $fd): void
    {
        $this->state->del($this->build_key($fd));
    }

    public function set(string $key, array $value): void
    {
        $this->state->set($key, $value);
    }

    public function get(string $key, string $field = null): mixed
    {
        return $this->state->get($key, $field);
    }

    public function count(): int
    {
        return $this->state->count();
    }

    protected function build_key(int $fd): string
    {
        return sprintf('fd:%d', $fd);
    }

    public function __destruct()
    {
        $this->state->destroy();
    }
}