<?php

namespace Tonysm\TurboLaravelTestHelpers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;
use Symfony\Component\DomCrawler\Crawler;
use Tonysm\TurboLaravel\Turbo;

class TurboLaravelTestHelpersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->environment('testing')) {
            TestResponse::macro('assertTurboStream', function () {
                return $this->assertHeader(
                    'Content-Type',
                    sprintf('%s; charset=UTF-8', Turbo::TURBO_STREAM_FORMAT)
                );
            });

            TestResponse::macro('assertHasTurboStream', function (string $target, ?string $action = null) {
                $crawler = (new Crawler($this->getContent()))->filter('turbo-stream');

                $results = $crawler->reduce(function (Crawler $dom) use ($target, $action) {
                    $actualTarget = $dom->attr('target');
                    $actualAction = $dom->attr('action');

                    return $actualTarget === $target && (!$action || $actualAction === $action);
                });

                Assert::assertTrue(
                    $results->count() >= 1,
                    sprintf(
                        'Expected to find %d matches for "%s", but there were %d.',
                        1,
                        "turbo-stream[target={$target}]" . ($action ? "[action={$action}]" : ""),
                        $results->count()
                    )
                );

                return $this;
            });

            TestResponse::macro('assertDoesntHaveTurboStream', function (string $target, ?string $action = null) {
                $crawler = (new Crawler($this->getContent()))->filter('turbo-stream');

                $results = $crawler->reduce(function (Crawler $dom) use ($target, $action) {
                    $actualTarget = $dom->attr('target');
                    $actualAction = $dom->attr('action');

                    if ($actualTarget === $target && ! $action) {
                        return true;
                    }

                    return $actualTarget === $target && $actualAction === $action;
                });

                Assert::assertTrue(
                    $results->count() === 0,
                    sprintf(
                        'Expected to NOT find matches for "%s", but there were %d.',
                        "turbo-stream[target={$target}]" . ($action ? "[action={$action}]" : ""),
                        $results->count()
                    )
                );

                return $this;
            });
        }
    }
}
