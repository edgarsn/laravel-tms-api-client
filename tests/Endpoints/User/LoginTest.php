<?php

declare(strict_types=1);

namespace Newman\LaravelTmsApiClient\Tests\Endpoints\User;

use Newman\LaravelTmsApiClient\Endpoints\User\Login;
use Newman\LaravelTmsApiClient\Tests\Endpoints\TestCase;

class LoginTest extends TestCase
{
    public function test_with_required_arguments(): void
    {
        $this->makeNullAuthEndpointTest(new Login('my@email.com', 'pass'), [], [
            'email' => 'my@email.com',
            'password' => 'pass'
        ]);
    }

    public function test_with_twoFaCode(): void
    {
        $endpoint = new Login('my@email.com', 'pass');

        $endpoint->twoFaCode('564389');

        $this->makeNullAuthEndpointTest($endpoint, [], [
            'email' => 'my@email.com',
            'password' => 'pass',
            'mfa' => [
                '2fa_code' => '564389',
            ],
        ]);
    }

    public function test_with_return(): void
    {
        $endpoint = new Login('my@email.com', 'pass');

        $endpoint->return(['client.limits']);

        $this->makeNullAuthEndpointTest($endpoint, [], [
            'email' => 'my@email.com',
            'password' => 'pass',
            'return' => ['client.limits'],
        ]);
    }
}
