<?php
declare(strict_types=1);


namespace SwooleGin\Stream;


use Psr\Http\Message\StreamInterface;
use RuntimeException;

class StringStream implements StreamInterface
{

    public function __construct(private string $stream)
    {
    }

    public function __toString(): string
    {
        return $this->stream;
    }

    public function close()
    {
        $this->stream = '';
    }

    public function detach()
    {
        $this->close();
        return null;
    }

    public function getSize(): ?int
    {
        return strlen($this->stream);
    }

    public function tell()
    {
        throw new RuntimeException('Cannot determine the position of a StringStream');
    }

    public function eof()
    {
        return empty($this->stream);
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new RuntimeException('Cannot seek a StringStream');
    }

    public function rewind()
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function write($string): int
    {
        $this->stream .= $string;

        return strlen($string);
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        if ($length > $this->getSize()) {
            $result = $this->stream;
            $this->stream = '';
        } else {
            $result = substr($this->stream, 0, $length);
            $this->stream = substr($this->stream, $length);
        }
        return $result;
    }

    public function getContents(): string
    {
        $result = $this->stream;

        $this->stream = '';

        return $result;
    }

    public function getMetadata($key = null): ?array
    {
        return $key ? null : [];
    }

}