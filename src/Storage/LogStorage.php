<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

use DateTimeInterface;
use Psr\Log\LoggerInterface;
use SpiralPackages\Profiler\Converter\ConverterInterface;
use SpiralPackages\Profiler\Converter\NullConverter;

final class LogStorage implements StorageInterface
{
    private LoggerInterface $logger;
    private ConverterInterface $converter;

    public function __construct(
        LoggerInterface $logger,
        ConverterInterface $converter = null
    ) {
        $this->logger = $logger;
        $this->converter = $converter ?? new NullConverter();
    }

    public function store(string $appName, array $tags, DateTimeInterface $date, array $data): void
    {
        $this->logger->info('Profile data', [
            'app' => $appName,
            'tags' => $tags,
            'date' => $date->format('Y-m-d H:i:s'),
            'data' => $this->converter->convert($data),
        ]);
    }
}
