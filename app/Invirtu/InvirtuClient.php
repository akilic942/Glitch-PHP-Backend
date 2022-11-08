<?php

namespace App\Invirtu;

use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use stdClass;

class InvirtuClient
{
    /**
     * @var HttpClient $httpClient
     */
    public $httpClient;

    /**
     * @var RequestFactory $requestFactory
     */
    public $requestFactory;

    /**
     * @var UriFactory $uriFactory
     */
    public $uriFactory;

    /**
     * @var string API or OAuth Token
     */
    public $apiToken;

    /**
     * @var string Thinkific Domain
     */
    public $domain;

    /**
     * @var string Thinkific API Version
     */
    private $version;

    /**
     * @var bool Use OAuth
     */
    private $is_oauth;

    /**
     * @var array $extraRequestHeaders
     */
    private $extraRequestHeaders;

    /**
     * @var array $rateLimitDetails
     */
    protected $rateLimitDetails = [];

    /**
     * @var InvirtuUsers $accounts
     */
    public $accounts;

    /**
     * @var InvirtuAuth $auth
     */
    public $auth;

    /**
     * @var InvirtuCohorts $cohorts
     */
    public $cohorts;

    /**
     * @var InvirtuEvents $events
     */
    public $events;

    /**
     * @var InvirtuVideos $videos
     */
    public $videos;

    /**
     * @var InvirtuProducts $products
     */
    public $products;

    /**
     * @var InvirtuTemplates $templates
     */
    public $templates;

    /**
     * @var InvirtuWidgets $widgets
     */
    public $widgets;

    /**
     * @var InvirtuOrganizers $organizers
     */
    public $organizers;


    public $invirtuApiEndPoint = 'https://bw.bingewave.com';

    /**
     * ThinkificClient constructor.
     *
     * @param string $apiToken App Token.
     * @param string $domain Domain
     * @param array $extraRequestHeaders Extra request headers to be sent in every api request
     * @param int $version API Version in use
     * @param bool $oauth Set true if using OAuth token instead of API key
     */
    public function __construct(string $apiToken, array $extraRequestHeaders = [], $version = 1, $oauth = false)
    {
        $this->accounts = new InvirtuAccounts($this);
        $this->auth = new InvirtuAuth($this);
        $this->cohorts = new InvirtuCohorts($this);
        $this->events = new InvirtuEvents($this);
        $this->organizers = new InvirtuOrganizers($this);
        $this->videos = new InvirtuVideos($this);
        $this->templates = new InvirtuTemplates($this);
        $this->widgets = new InvirtuWidgets($this);
       
        //$this->oauth = new ThinkificOAuth($this);

        $this->apiToken = $apiToken;
        $this->extraRequestHeaders = $extraRequestHeaders;
        $this->version = $version;
        $this->is_oauth = $oauth;

        $this->httpClient = $this->getDefaultHttpClient();
        $this->requestFactory = MessageFactoryDiscovery::find();
        $this->uriFactory = UriFactoryDiscovery::find();
    }

    /**
     * Allows us to change the end point
     * @param $end_point
     */
    public function setEndpoint($end_point)
    {
        $this->invirtuApiEndPoint = $end_point;
    }

    /**
     * Sets the HTTP client.
     * e.g. https://api.thinkific.com/api/v2 for Webhooks
     *
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Sets the request factory.
     *
     * @param RequestFactory $requestFactory
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Sets the URI factory.
     *
     * @param UriFactory $uriFactory
     */
    public function setUriFactory(UriFactory $uriFactory)
    {
        $this->uriFactory = $uriFactory;
    }

    /**
     * Sets true if using OAuth
     *
     * @param bool $is_oauth
     */
    public function setOAuth($is_oauth)
    {
        $this->is_oauth = $is_oauth;
    }

    /**
     * Determines if a response has more pages
     *
     * @param stdClass $response
     * @return bool
     */
    public function hasMore(stdClass $response)
    {

        if (isset($response->meta->pagination)) {
            return $response->meta->pagination->current_page < $response->meta->pagination->next_page;
        }

        return false;
    }

    /**
     * Returns the next page number of a response
     *
     * @param string $path
     * @param stdClass $response
     * @return int
     * @throws ClientExceptionInterface
     */
    public function nextPage(stdClass $response)
    {
        return isset($response->meta->pagination->next_page) ? $response->meta->pagination->next_page : 1;
    }

    /**
     * Sends POST request to Thinkific API.
     *
     * @param string $endpoint
     * @param array $json
     * @return stdClass
     */
    public function post($endpoint, $json, $queryParams = [])
    {
        $uri = $this->uriFactory->createUri($this->invirtuApiEndPoint . $endpoint);
        
        if (!empty($queryParams)) {
            $uri = $uri->withQuery(http_build_query($queryParams));
        }

        $response = $this->sendRequest('POST', $uri, $json);
        return $this->handleResponse($response);
    }

    /**
     * Sends PUT request to Thinkific API.
     *
     * @param string $endpoint
     * @param array $json
     * @return stdClass
     */
    public function put($endpoint, $json, $queryParams = [])
    {
        $uri = $this->uriFactory->createUri($this->invirtuApiEndPoint . $endpoint);
        
        if (!empty($queryParams)) {
            $uri = $uri->withQuery(http_build_query($queryParams));
        }

        $response = $this->sendRequest('PUT', $uri, $json);
        return $this->handleResponse($response);
    }

    /**
     * Sends DELETE request to Thinkific API.
     *
     * @param string $endpoint
     * @param array $json
     * @return stdClass
     */
    public function delete($endpoint, $json = [], $queryParams = [])
    {
        $uri = $this->uriFactory->createUri($this->invirtuApiEndPoint . $endpoint);

        if (!empty($queryParams)) {
            $uri = $uri->withQuery(http_build_query($queryParams));
        }

        $response = $this->sendRequest('DELETE', $uri, $json);
        return $this->handleResponse($response);
    }

    /**
     * Sends GET request to Thinkific API.
     *
     * @param string $endpoint
     * @param array $queryParams
     * @return stdClass
     */
    public function get($endpoint, $queryParams = [])
    {

        $uri = $this->uriFactory->createUri($this->invirtuApiEndPoint . $endpoint);

        if (!empty($queryParams)) {
            $uri = $uri->withQuery(http_build_query($queryParams));
        }

        $response = $this->sendRequest('GET', $uri);

        return $this->handleResponse($response);
    }

    /**
     * Gets the rate limit details.
     *
     * @return array
     */
    public function getRateLimitDetails()
    {
        return $this->rateLimitDetails;
    }

    /**
     * @return HttpClient
     */
    private function getDefaultHttpClient()
    {
        return new PluginClient(
            HttpClientDiscovery::find(),
            [new ErrorPlugin()]
        );
    }

    /**
     * @return array
     */
    private function getRequestHeaders()
    {
        return array_merge(
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            $this->extraRequestHeaders
        );
    }

    /**
     * @return array
     */
    private function getRequestAuthHeaders()
    {

        $headers = [
            'Authorization' => 'Bearer ' . $this->apiToken
        ];

        /*
        if ($this->is_oauth) {
            $headers = [
                'Authorization' => 'Bearer ' . $this->apiToken
            ];
        } else {
            $headers = [
                'X-Auth-API-Key' => $this->apiToken,
                'X-Auth-Subdomain' => $this->domain
            ];
        }*/

        return $headers;
    }

    /**
     * @param string $method
     * @param string|UriInterface $uri
     * @param array|string|null $body
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    private function sendRequest($method, $uri, $body = null)
    {
        $headers = $this->getRequestHeaders();

        $authHeaders = $this->getRequestAuthHeaders();

        $headers = array_merge($headers, $authHeaders);

        $body = is_array($body) ? json_encode($body) : $body;

        $request = $this->requestFactory->createRequest($method, $uri, $headers, $body);

        return $this->httpClient->sendRequest($request);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     */
    public function handleResponse(ResponseInterface $response)
    {
        $this->setRateLimitDetails($response);

        $stream = $response->getBody()->getContents();

        return json_decode($stream);
    }

    /**
     * @param ResponseInterface $response
     */
    private function setRateLimitDetails(ResponseInterface $response)
    {
        $this->rateLimitDetails = [
            'reset_at' => $response->hasHeader('RateLimit-Reset')
                ? (int)$response->getHeader('RateLimit-Reset')
                : null,
        ];
    }
}
