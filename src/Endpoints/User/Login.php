<?php

declare(strict_types=1);

namespace Newman\LaravelBackscreenApiClient\Endpoints\User;

use Newman\LaravelBackscreenApiClient\AbstractEndpoint;
use Newman\LaravelBackscreenApiClient\Contracts\EndpointContract;
use Newman\LaravelBackscreenApiClient\Enums\AuthMethodEnum;
use Newman\LaravelBackscreenApiClient\Enums\HttpMethodEnum;
use Newman\LaravelBackscreenApiClient\HttpClient\PendingRequest;

/**
 * @link https://api.cloudycdn.services/api/v5/docs#/operations/User/Login
 */
class Login extends AbstractEndpoint implements EndpointContract
{
    protected ?string $twoFaCode = null;

    /**
     * @var array<string>|null
     */
    protected ?array $return = null;

    public function __construct(protected string $email, protected string $password) {}

    public function twoFaCode(?string $code): static
    {
        $this->twoFaCode = $code;

        return $this;
    }

    /**
     * @param  array<string>|null  $return
     * @return $this
     */
    public function return(?array $return): static
    {
        $this->return = $return;

        return $this;
    }

    /**
     * Define which authentication methods are allowed to call this endpoint.
     *
     * @return AuthMethodEnum[]
     */
    public function allowedAuthMethods(): array
    {
        return [AuthMethodEnum::NULL];
    }

    /**
     * HTTP Method to use for request.
     */
    public function useHttpMethod(): HttpMethodEnum
    {
        return HttpMethodEnum::POST;
    }

    /**
     * Endpoint url.
     */
    public function endpointUrl(): string
    {
        return '/User/Login';
    }

    /**
     * Prepares HTTP request for this endpoint.
     */
    public function prepareHttpRequest(PendingRequest $http): void
    {
        $data = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if ($this->twoFaCode !== null) {
            $data['mfa'] = [
                '2fa_code' => $this->twoFaCode,
            ];
        }

        if ($this->return !== null) {
            $data['return'] = $this->return;
        }

        $http->withData($data);
    }
}
