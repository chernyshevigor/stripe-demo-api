<?php

namespace App\Helpers\Traits;

use App\Helpers\MainService\ClientContract;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\{ServerRequest, Uri};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface, UriInterface};

trait ClientTrait
{
    private string $checkHeaderName = 'Referer';
    private UriInterface $uri;
    private string $apiPath;

    public function setApiPath(string $apiPath = ''): ClientContract
    {
        $this->apiPath = $apiPath;
        return $this;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function performRequest(string $action, ServerRequestInterface $origRequest, array $options): mixed
    {
        $httpClient = $this->getHttpClient();
        $request = $this->createRequest($action, $origRequest);
        /** @var ResponseInterface $response */
        $response = $httpClient->send(
            $request,
            array_merge_recursive(
                $this->getHttpClientOptions($origRequest),
                $options
            )
        );
        $this->throwExceptionIfError($request, $response);
        return \json_decode($response->getBody());
    }

    private function getHttpClient(): Client
    {
        return new Client();
    }

    private function createRequest(string $action, ServerRequestInterface $origRequest): ServerRequestInterface
    {
        $request = new ServerRequest('POST', $this->getUri()->withPath($this->getActionPath($action)));
        $checkHeader = $origRequest->getHeader($this->checkHeaderName);
        return $checkHeader
            ? $request->withHeader($this->checkHeaderName, $checkHeader)
            : $request;
    }

    private function getActionPath(string $action): string
    {
        return $this->apiPath
            ? '/' . $this->apiPath . $action
            : $action;
    }

    private function getHttpClientOptions(ServerRequestInterface $request): array
    {
        $cookieJar = CookieJar::fromArray(
            $request->getCookieParams(),
            $this->getUri()->getHost()
        );
        return [
            'cookies' => $cookieJar,
            'json' => []
        ];
    }

    private function throwExceptionIfError(ServerRequestInterface $request, ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 300 || $statusCode < 200) {
            throw new RequestException('Something wrong', $request, $response);
        }
        $json = \json_decode($response->getBody());
        if (empty($json)) {
            throw new RequestException('Invalid response', $request, $response);
        }
    }

    public function setUri(string $uri): ClientContract
    {
        $this->uri = new Uri($uri);
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }
}
