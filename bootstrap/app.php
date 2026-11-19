<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'validator.only'       => \App\Http\Middleware\ValidatorOnly::class,
            'super_admin.only'     => \App\Http\Middleware\SuperAdminOnly::class,
            'knowledge_gap.access' => \App\Http\Middleware\KnowledgeGapAccess::class,
            'skms.timeout'         => \App\Http\Middleware\SkmsSessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
