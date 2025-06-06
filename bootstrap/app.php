<?php

use Illuminate\Foundation\Application;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware as SpatieRoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware as SpatiePermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware as SpatieRoleOrPermissionMiddleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => SpatieRoleMiddleware::class,
        'permission' => SpatiePermissionMiddleware::class,
        'role_or_permission' => SpatieRoleOrPermissionMiddleware::class,
    ]);
})


    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
