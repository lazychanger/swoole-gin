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
    protected array $default_options = [
        'original' => [
            'host' => '',
            'original_host' => ''
        ]
    ];

    protected array $options = [];
    protected ClientInterface $client;

    public function __construct(array $options = [], ClientInterface $client = null)
    {
        $this->options = array_merge($this->default_options, $options);

        $this->client = $client ?? new Client();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function handler(RequestInterface $request): ResponseInterface
    {
        $request->withUri(new Uri($this->options['original']['host']), true);

        if (!empty($this->options['original']['original_host'])) {
            $request->withHeader('Host', $this->options['original']['original_host']);
        }

        return $this->client->sendRequest($request);
    }
}