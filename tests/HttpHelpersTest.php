<?php

namespace Tonysm\TurboLaravelTestHelpers\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tonysm\TurboLaravel\Http\Middleware\TurboMiddleware;
use Tonysm\TurboLaravel\TurboFacade;
use Tonysm\TurboLaravelTestHelpers\Testing\InteractsWithTurbo;

class HttpHelpersTest extends TestCase
{
    use InteractsWithTurbo;

    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/stubs/views');
    }

    /** @test */
    public function sends_turbo_requests()
    {
        Route::get('test-me', function () {
            if (request()->wantsTurboStream()) {
                return 'wants turbo stream';
            }

            return 'doesnt want turbo stream';
        });

        $this->get('test-me')->assertSee('doesnt want turbo stream');
        $this->turbo()->get('test-me')->assertSee('wants turbo stream');
    }

    /** @test */
    public function asserts_turbo_stream_tags()
    {
        Route::post('test-me', function () {
            $model = TestModel::create(['name' => 'Hello']);

            if (request()->wantsTurboStream()) {
                return response()->turboStream($model);
            }

            return 'doesnt want turbo stream';
        });

        $response = $this->turbo()->post('test-me');

        $response->assertTurboStream();
        $response->assertHasTurboStream('test_models', 'append');
        $response->assertHasTurboStream('test_models');
        $response->assertDoesntHaveTurboStream('test_model_123');
        $response->assertDoesntHaveTurboStream('test_model_123', 'append');
    }

    /** @test */
    public function assert_has_turbo_stream_when_multiple_streams()
    {
        Route::post('test-me', function () {
            if (request()->wantsTurboStream()) {
                return response()->turboStreamView(view('test_models/turbo_stream'));
            }

            return 'doesnt want turbo stream';
        });

        $response = $this->turbo()->post('test-me');

        $response->assertTurboStream();
        $response->assertHasTurboStream('test_models', 'append');
        $response->assertHasTurboStream('empty_test_models', 'remove');
        $response->assertDoesntHaveTurboStream('test_model_123');
    }

    /** @test */
    public function sends_turbo_native_requests()
    {
        Route::get('test-me', function () {
            if (TurboFacade::isTurboNativeVisit()) {
                return 'hello from turbo native';
            }

            return 'not from turbo native';
        })->middleware(TurboMiddleware::class);

        $this->get('test-me')->assertSee('not from turbo native');
        $this->turboNative()->get('test-me')->assertSee('hello from turbo native');
    }

    /** @test */
    public function detects_turbo_native_from_requests_in_view()
    {
        Route::get('test-me', function () {
            return view('turbo_native');
        })->middleware(TurboMiddleware::class);

        $this->get('test-me')->assertSee('Not a Turbo Native request');
        $this->turboNative()->get('test-me')->assertSee('Hello from Turbo Native');
    }
}
