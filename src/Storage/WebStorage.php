<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

use DateTimeInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SpiralPackages\Profiler\Converter\ConverterInterface;
use SpiralPackages\Profiler\Converter\NullConverter;

use function gethostname;

final class WebStorage implements StorageInterface
{
    private HttpClientInterface $httpClient;
    private string $endpoint;
    private string $method;
    private array $options;
    private ConverterInterface $converter;

    public function __construct(
        HttpClientInterface $httpClient,
        string $endpoint,
        string $method,
        array $options,
        ?ConverterInterface $converter
    ) {
        $this->converter = $converter ?? new NullConverter();
        $this->options = $options;
        $this->method = $method;
        $this->endpoint = $endpoint;
        $this->httpClient = $httpClient;
    }

    public function store(string $appName, array $tags, DateTimeInterface $date, array $data): void
    {
        $this->options['json'] = [
            'profile' => $this->converter->convert($data),
            'tags' => $tags,
            'app_name' => $appName,
            'hostname' => gethostname(),
            'date' => $date->getTimestamp(),
        ];

        $this->httpClient->request($this->method, $this->endpoint, $this->options);
    }
}
