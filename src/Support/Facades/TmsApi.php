<?php

declare(strict_types=1);

namespace Newman\LaravelBackscreenApiClient\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Newman\LaravelBackscreenApiClient\Contracts\AuthMethodContract;
use Newman\LaravelBackscreenApiClient\Contracts\ClientContract;
use Newman\LaravelBackscreenApiClient\Contracts\TmsApiContract;
use Newman\LaravelBackscreenApiClient\Support\TmsApiFake;

/**
 * @method static ClientContract client(string $name)
 * @method static ClientContract nullClient()
 * @method static ClientContract createClient(string $name, AuthMethodContract $auth)
 * @method static array<string, ClientContract> getClients()
 */
class TmsApi extends Facade
{
    /**
     * Replace the bound instance with a fake.
     */
    public static function fake(): TmsApiFake
    {
        /** @var TmsApiContract $facadeRoot */
        $facadeRoot = static::getFacadeRoot();

        static::swap($fake = new TmsApiFake($facadeRoot));

        return $fake;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TmsApiContract::class;
    }
}
