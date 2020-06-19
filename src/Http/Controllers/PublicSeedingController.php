<?php

namespace Votintsev\PublicSeeding\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PublicSeedingController
{
    public function callArtisan(Request $request)
    {
        $request->validate([
            'command' => 'required',
            'parameters' => 'array'
        ]);

        Artisan::call($request->command, $request->input('parameters', []));
        return Artisan::output();
    }

    public function callFactory(Request $request)
    {
        $request->validate([
            'class' => 'required',
            'amount' => 'integer|gt:0',
            'attributes' => 'array'
        ]);

        $class = $this->resolveClass($request->class);

        return factory($class, $request->amount)->create($request->input('attributes', []));
    }

    protected function resolveClass($className)
    {
        if (! mb_strpos($className, '\\')) {
            $className = config('public-seeding.models_namespace', 'App') . '\\' . $className;
        }

        return $className;
    }
}
