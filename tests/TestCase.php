<?php

namespace Tonysm\TurboLaravelTestHelpers\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench;
use Tonysm\TurboLaravel\TurboServiceProvider;
use Tonysm\TurboLaravelTestHelpers\TurboLaravelTestHelpersServiceProvider;

class TestCase extends Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            TurboServiceProvider::class,
            TurboLaravelTestHelpersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    private function setUpDatabase(Application $app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('parent_id')->nullable();
            $table->timestamps();
        });
    }
}
