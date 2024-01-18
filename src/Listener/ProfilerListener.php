<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Listener;

use SpiralPackages\Profiler\Profiler;

final class ProfilerListener
{
    private Profiler $profiler;

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function onKernelRequest($event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->profiler->start();
    }

    public function onKernelTerminate($request, $response): void
    {
        if (!$request->isMasterRequest()) {
            return;
        }

        $this->profiler->end();
    }
}
