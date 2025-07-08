<?php

use App\Http\Middleware\CheckLastActivity;
use App\Http\Middleware\CheckTaskStatus;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'last.activity'=> \App\Http\Middleware\CheckLastActivity::class,
            'ip.restrict' => \App\Http\Middleware\RestrictLoginByIP::class,
            'task_status' => \App\Http\Middleware\CheckTaskStatus::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\CheckTaskStatus::class,
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
