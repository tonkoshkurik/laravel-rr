<?php

use Spiral\RoadRunnerLaravel\Events;
use Spiral\RoadRunnerLaravel\Defaults;
use Spiral\RoadRunnerLaravel\Listeners;

return [
    /*
    |--------------------------------------------------------------------------
    | Force HTTPS Schema Usage
    |--------------------------------------------------------------------------
    |
    | Set this value to `true` if your application uses HTTPS (required for
    | correct links generation, for example).
    |
    */

    'force_https' => (bool) env('APP_FORCE_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    |
    | Worker provided by this package allows to interacts with request
    | processing loop using application events.
    |
    | Feel free to add your own event listeners.
    |
    */

    'listeners' => [
        Events\BeforeLoopStartedEvent::class => [
            ...Defaults::beforeLoopStarted(),
        ],

        Events\BeforeLoopIterationEvent::class => [
            ...Defaults::beforeLoopIteration(),
            // Listeners\ResetLaravelScoutListener::class,     // for <https://github.com/laravel/scout>
            // Listeners\ResetLaravelSocialiteListener::class, // for <https://github.com/laravel/socialite>
            // Listeners\ResetInertiaListener::class,          // for <https://github.com/inertiajs/inertia-laravel>
        ],

        Events\BeforeRequestHandlingEvent::class => [
            ...Defaults::beforeRequestHandling(),
            Listeners\InjectStatsIntoRequestListener::class,
        ],

        Events\AfterRequestHandlingEvent::class => [
            ...Defaults::afterRequestHandling(),
        ],

        Events\AfterLoopIterationEvent::class => [
            ...Defaults::afterLoopIteration(),
            Listeners\RunGarbageCollectorListener::class, // keep the memory usage low
        ],

        Events\AfterLoopStoppedEvent::class => [
            ...Defaults::afterLoopStopped(),
        ],

        Events\LoopErrorOccurredEvent::class => [
            ...Defaults::loopErrorOccurred(),
            Listeners\SendExceptionToStderrListener::class,
            Listeners\StopWorkerListener::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Containers Pre Resolving / Clearing
    |--------------------------------------------------------------------------
    |
    | The bindings listed below will be resolved before the events loop
    | starting. Clearing a binding will force the container to resolve that
    | binding again when asked.
    |
    | Feel free to add your own bindings here.
    |
    */

    'warm' => [
        ...Defaults::servicesToWarm(),
    ],

    'clear' => [
        ...Defaults::servicesToClear(),
        // 'auth', // is not required for Laravel >= v8.35
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Providers
    |--------------------------------------------------------------------------
    |
    | Providers that will be registered on every request.
    |
    | Feel free to add your service-providers here.
    |
    */

    'reset_providers' => [
        ...Defaults::providersToReset(),
        // Illuminate\Auth\AuthServiceProvider::class,             // is not required for Laravel >= v8.35
        // Illuminate\Pagination\PaginationServiceProvider::class, // is not required for Laravel >= v8.35
    ],
];
