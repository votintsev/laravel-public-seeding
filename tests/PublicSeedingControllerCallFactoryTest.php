<?php
namespace Votintsev\PublicSeeding\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Votintsev\PublicSeeding\Http\Controllers\PublicSeedingController;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class PublicSeedingControllerCallFactoryTest extends TestCase
{
    public function testCallFactorySuccess()
    {
        $query = [
            'class' => 'User',
        ];

        $this->callFactorySuccess($query);
    }

    public function testCallFactorySuccessAmount()
    {
        $query = [
            'class' => 'User',
            'amount' => 3,
        ];

        $this->callFactorySuccess($query);
    }

    public function testCallFactorySuccessAttributes()
    {
        $query = [
            'class' => 'User',
            'amount' => 3,
            'attributes' => [
                'name' => 'John Doe'
            ]
        ];

        $this->callFactorySuccess($query);
    }

    public function testCallFactorySuccessFailValidation()
    {
        $query = [
            'class' => '',
        ];

        $controller = new PublicSeedingController();
        $request = Request::create('/', 'POST', $query);

        $this->expectException(ValidationException::class);
        $controller->callFactory($request);
    }

    private function callFactorySuccess($query)
    {
        $this->mock(EloquentFactory::class, function ($mock) use ($query) {
            $mock->shouldReceive('of')->once()->andReturn($mock);

            if (isset($query['amount'])) {
                $mock->shouldReceive('times')->once()->with($query['amount'])->andReturn($mock);
            }

            $mock->shouldReceive('create')->once()->with($query['attributes'] ?? [])->andReturn('RightAnswer');
        });

        $controller = new PublicSeedingController();
        $request = Request::create('/', 'POST', $query);
        $result = $controller->callFactory($request);

        $this->assertEquals('RightAnswer', $result);
    }

    public function testResolveClass()
    {
        $getResolveClass = function ($className) {
            return $this->resolveClass($className);
        };

        $controller = new PublicSeedingController();

        $resolve = $getResolveClass->call($controller, 'User');
        $this->assertEquals('App\User', $resolve);

        $resolve = $getResolveClass->call($controller, 'App\Models\User');
        $this->assertEquals('App\Models\User', $resolve);
    }

    public function testResolveClassNamespaceFromConfig()
    {
        $getResolveClass = function ($className) {
            return $this->resolveClass($className);
        };

        $controller = new PublicSeedingController();

        Config::set('public-seeding.models_namespace', 'App\Models');

        $resolve = $getResolveClass->call($controller, 'User');
        $this->assertEquals('App\Models\User', $resolve);
    }
}
