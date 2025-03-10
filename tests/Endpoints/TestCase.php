<?php

declare(strict_types=1);

namespace Newman\LaravelBackscreenApiClient\Tests\Endpoints;

use Illuminate\Http\Client\Response;
use Newman\LaravelBackscreenApiClient\Auth\ApiKeyAuthMethod;
use Newman\LaravelBackscreenApiClient\Auth\BasicAuthMethod;
use Newman\LaravelBackscreenApiClient\Auth\BearerAuthMethod;
use Newman\LaravelBackscreenApiClient\Auth\NullAuthMethod;
use Newman\LaravelBackscreenApiClient\Client;
use Newman\LaravelBackscreenApiClient\Contracts\ClientContract;
use Newman\LaravelBackscreenApiClient\Contracts\EndpointContract;

abstract class TestCase extends \Newman\LaravelBackscreenApiClient\Tests\TestCase
{
    protected function makeBasicAuthEndpointTest(EndpointContract $endpoint, array $query = [], array|string|null $body = null, ?array $fakeResponse = null): Response
    {
        $client = new Client(new BasicAuthMethod('user', 'pass'));

        return $this->makeEndpointTestWithCilent($client, $endpoint, $query, $body, $fakeResponse);
    }

    protected function makeBearerAuthEndpointTest(EndpointContract $endpoint, array $query = [], array|string|null $body = null, ?array $fakeResponse = null): Response
    {
        $client = new Client(new BearerAuthMethod('abc'));

        return $this->makeEndpointTestWithCilent($client, $endpoint, $query, $body, $fakeResponse);
    }

    protected function makeApiKeyAuthEndpointTest(EndpointContract $endpoint, array $query = [], array|string|null $body = null, ?array $fakeResponse = null): Response
    {
        $client = new Client(new ApiKeyAuthMethod('abc'));

        return $this->makeEndpointTestWithCilent($client, $endpoint, $query, $body, $fakeResponse);
    }

    protected function makeNullAuthEndpointTest(EndpointContract $endpoint, array $query = [], array|string|null $body = null, ?array $fakeResponse = null): Response
    {
        $client = new Client(new NullAuthMethod);

        return $this->makeEndpointTestWithCilent($client, $endpoint, $query, $body, $fakeResponse);
    }

    protected function makeEndpointTestWithCilent(ClientContract $client, EndpointContract $endpoint, array $query = [], array|string|null $body = null, ?array $fakeResponse = null): Response
    {
        $fakeUrl = 'api.cloudycdn.services/api/v5'.$endpoint->endpointUrl();

        if ($query) {
            $fakeUrl .= '?'.http_build_query($query, '', null, PHP_QUERY_RFC3986);
        }

        $fakeResponse = $fakeResponse !== null ? $fakeResponse : ['msg' => '', 'code' => 0];

        $factory = $client->buildHttpFactory();
        $factory->preventStrayRequests();
        $factory->fake([
            $fakeUrl => $factory->response($fakeResponse),
        ]);

        $response = $client->run($endpoint);

        if ($body !== null) {
            $this->assertEquals(is_array($body) ? json_encode($body) : $body, (string) $response->transferStats->getRequest()->getBody());
        }

        $this->assertEquals($fakeResponse, $response->json());

        return $response;
    }
}
