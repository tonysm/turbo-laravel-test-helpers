<?php

namespace Tonysm\TurboLaravelTestHelpers\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tonysm\TurboLaravelTestHelpers\Testing\InteractsWithTurbo;

class HttpHelpersTest extends TestCase
{
    use InteractsWithTurbo;

    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs/views');
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
}
