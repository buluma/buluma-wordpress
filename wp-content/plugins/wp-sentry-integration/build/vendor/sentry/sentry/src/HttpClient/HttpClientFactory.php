<?php

declare (strict_types=1);
namespace Sentry\HttpClient;

use WPSentry\ScopedVendor\GuzzleHttp\RequestOptions as GuzzleHttpClientOptions;
use WPSentry\ScopedVendor\Http\Adapter\Guzzle6\Client as GuzzleHttpClient;
use WPSentry\ScopedVendor\Http\Client\Common\Plugin\AuthenticationPlugin;
use WPSentry\ScopedVendor\Http\Client\Common\Plugin\DecoderPlugin;
use WPSentry\ScopedVendor\Http\Client\Common\Plugin\ErrorPlugin;
use WPSentry\ScopedVendor\Http\Client\Common\Plugin\HeaderSetPlugin;
use WPSentry\ScopedVendor\Http\Client\Common\Plugin\RetryPlugin;
use WPSentry\ScopedVendor\Http\Client\Common\PluginClient;
use WPSentry\ScopedVendor\Http\Client\Curl\Client as CurlHttpClient;
use WPSentry\ScopedVendor\Http\Client\HttpAsyncClient as HttpAsyncClientInterface;
use WPSentry\ScopedVendor\Http\Discovery\HttpAsyncClientDiscovery;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface;
use Sentry\HttpClient\Authentication\SentryAuthentication;
use Sentry\HttpClient\Plugin\GzipEncoderPlugin;
use Sentry\Options;
use WPSentry\ScopedVendor\Symfony\Component\HttpClient\HttpClient as SymfonyHttpClient;
use WPSentry\ScopedVendor\Symfony\Component\HttpClient\HttplugClient as SymfonyHttplugClient;
/**
 * Default implementation of the {@HttpClientFactoryInterface} interface that uses
 * Httplug to autodiscover the HTTP client if none is passed by the user.
 */
final class HttpClientFactory implements \Sentry\HttpClient\HttpClientFactoryInterface
{
    /**
     * @var int The timeout of the request in seconds
     */
    private const DEFAULT_HTTP_TIMEOUT = 5;
    /**
     * @var int The default number of seconds to wait while trying to connect
     *          to a server
     */
    private const DEFAULT_HTTP_CONNECT_TIMEOUT = 2;
    /**
     * @var UriFactoryInterface The PSR-7 URI factory
     */
    private $uriFactory;
    /**
     * @var ResponseFactoryInterface The PSR-7 response factory
     */
    private $responseFactory;
    /**
     * @var StreamFactoryInterface The PSR-17 stream factory
     */
    private $streamFactory;
    /**
     * @var HttpAsyncClientInterface|null The HTTP client
     */
    private $httpClient;
    /**
     * @var string The name of the SDK
     */
    private $sdkIdentifier;
    /**
     * @var string The version of the SDK
     */
    private $sdkVersion;
    /**
     * Constructor.
     *
     * @param UriFactoryInterface           $uriFactory      The PSR-7 URI factory
     * @param ResponseFactoryInterface      $responseFactory The PSR-7 response factory
     * @param StreamFactoryInterface        $streamFactory   The PSR-17 stream factory
     * @param HttpAsyncClientInterface|null $httpClient      The HTTP client
     * @param string                        $sdkIdentifier   The SDK identifier
     * @param string                        $sdkVersion      The SDK version
     */
    public function __construct(\WPSentry\ScopedVendor\Psr\Http\Message\UriFactoryInterface $uriFactory, \WPSentry\ScopedVendor\Psr\Http\Message\ResponseFactoryInterface $responseFactory, \WPSentry\ScopedVendor\Psr\Http\Message\StreamFactoryInterface $streamFactory, ?\WPSentry\ScopedVendor\Http\Client\HttpAsyncClient $httpClient, string $sdkIdentifier, string $sdkVersion)
    {
        $this->uriFactory = $uriFactory;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->httpClient = $httpClient;
        $this->sdkIdentifier = $sdkIdentifier;
        $this->sdkVersion = $sdkVersion;
    }
    /**
     * {@inheritdoc}
     */
    public function create(\Sentry\Options $options) : \WPSentry\ScopedVendor\Http\Client\HttpAsyncClient
    {
        if (null === $options->getDsn()) {
            throw new \RuntimeException('Cannot create an HTTP client without the Sentry DSN set in the options.');
        }
        $httpClient = $this->httpClient;
        if (null !== $httpClient && null !== $options->getHttpProxy()) {
            throw new \RuntimeException('The "http_proxy" option does not work together with a custom HTTP client.');
        }
        if (null === $httpClient) {
            if (\class_exists(\WPSentry\ScopedVendor\Symfony\Component\HttpClient\HttplugClient::class)) {
                $symfonyConfig = ['max_duration' => self::DEFAULT_HTTP_TIMEOUT];
                if (null !== $options->getHttpProxy()) {
                    $symfonyConfig['proxy'] = $options->getHttpProxy();
                }
                /** @psalm-suppress UndefinedClass */
                $httpClient = new \WPSentry\ScopedVendor\Symfony\Component\HttpClient\HttplugClient(\WPSentry\ScopedVendor\Symfony\Component\HttpClient\HttpClient::create($symfonyConfig));
            } elseif (\class_exists(\WPSentry\ScopedVendor\Http\Adapter\Guzzle6\Client::class)) {
                /** @psalm-suppress UndefinedClass */
                $guzzleConfig = [\WPSentry\ScopedVendor\GuzzleHttp\RequestOptions::TIMEOUT => self::DEFAULT_HTTP_TIMEOUT, \WPSentry\ScopedVendor\GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => self::DEFAULT_HTTP_CONNECT_TIMEOUT];
                if (null !== $options->getHttpProxy()) {
                    /** @psalm-suppress UndefinedClass */
                    $guzzleConfig[\WPSentry\ScopedVendor\GuzzleHttp\RequestOptions::PROXY] = $options->getHttpProxy();
                }
                /** @psalm-suppress InvalidPropertyAssignmentValue */
                $httpClient = \WPSentry\ScopedVendor\Http\Adapter\Guzzle6\Client::createWithConfig($guzzleConfig);
            } elseif (\class_exists(\WPSentry\ScopedVendor\Http\Client\Curl\Client::class)) {
                $curlConfig = [\CURLOPT_TIMEOUT => self::DEFAULT_HTTP_TIMEOUT, \CURLOPT_CONNECTTIMEOUT => self::DEFAULT_HTTP_CONNECT_TIMEOUT];
                if (null !== $options->getHttpProxy()) {
                    $curlConfig[\CURLOPT_PROXY] = $options->getHttpProxy();
                }
                /** @psalm-suppress InvalidPropertyAssignmentValue */
                $httpClient = new \WPSentry\ScopedVendor\Http\Client\Curl\Client(null, null, $curlConfig);
            } elseif (null !== $options->getHttpProxy()) {
                throw new \RuntimeException('The "http_proxy" option requires either the "php-http/curl-client" or the "php-http/guzzle6-adapter" package to be installed.');
            }
        }
        if (null === $httpClient) {
            $httpClient = \WPSentry\ScopedVendor\Http\Discovery\HttpAsyncClientDiscovery::find();
        }
        $httpClientPlugins = [new \WPSentry\ScopedVendor\Http\Client\Common\Plugin\HeaderSetPlugin(['User-Agent' => $this->sdkIdentifier . '/' . $this->sdkVersion]), new \WPSentry\ScopedVendor\Http\Client\Common\Plugin\AuthenticationPlugin(new \Sentry\HttpClient\Authentication\SentryAuthentication($options, $this->sdkIdentifier, $this->sdkVersion)), new \WPSentry\ScopedVendor\Http\Client\Common\Plugin\RetryPlugin(['retries' => $options->getSendAttempts()]), new \WPSentry\ScopedVendor\Http\Client\Common\Plugin\ErrorPlugin()];
        if ($options->isCompressionEnabled()) {
            $httpClientPlugins[] = new \Sentry\HttpClient\Plugin\GzipEncoderPlugin($this->streamFactory);
            $httpClientPlugins[] = new \WPSentry\ScopedVendor\Http\Client\Common\Plugin\DecoderPlugin();
        }
        return new \WPSentry\ScopedVendor\Http\Client\Common\PluginClient($httpClient, $httpClientPlugins);
    }
}
