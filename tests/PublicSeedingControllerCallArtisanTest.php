<?php
namespace Votintsev\PublicSeeding\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Votintsev\PublicSeeding\Http\Controllers\PublicSeedingController;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Votintsev\PublicSeeding\Tests\Fixtures\User;

class PublicSeedingControllerCallArtisanTest extends TestCase
{
    public function testCallArtisanSuccess()
    {
        $query = [
            'command' => 'SuperCommand',
            'parameters' => ['first' => 'yeah']
        ];

        Artisan::shouldReceive('call')
            ->with($query['command'], $query['parameters'])
            ->once();

        Artisan::shouldReceive('output')
            ->once();

        $controller = new PublicSeedingController();
        $request = Request::create('/', 'POST', $query);
        $controller->callArtisan($request);
    }

    public function testCallArtisanSuccessEmptyParameters()
    {
        $query = [
            'command' => 'SuperCommand',
        ];

        Artisan::shouldReceive('call')
            ->with($query['command'], [])
            ->once();
        Artisan::shouldReceive('output')
            ->once();

        $controller = new PublicSeedingController();
        $request = Request::create('/', 'POST', $query);
        $controller->callArtisan($request);
    }


    public function testCallArtisanFailValidationCommand()
    {
        $this->failValidation([
            'command' => '',
        ]);
    }


    public function testCallArtisanFailValidationParameters()
    {
        $this->failValidation([
            'command' => 'correct',
            'parameters' => 'not_array'
        ]);
    }

    private function failValidation($query)
    {
        Artisan::shouldReceive('call')
            ->never();

        $controller = new PublicSeedingController();
        $request = Request::create('/', 'POST', $query);

        $this->expectException(ValidationException::class);
        $controller->callArtisan($request);
    }
}