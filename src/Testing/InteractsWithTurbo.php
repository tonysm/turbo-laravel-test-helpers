<?php

namespace Tonysm\TurboLaravelTestHelpers\Testing;

use Tonysm\TurboLaravel\Turbo;

/**
 * @mixin \Illuminate\Foundation\Testing\Concerns\MakesHttpRequests
 */
trait InteractsWithTurbo
{
    public function turbo(): self
    {
        return $this->withHeader('Accept', Turbo::TURBO_STREAM_FORMAT);
    }
}
