<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\Http\Client\Common\Plugin;

use WPSentry\ScopedVendor\Http\Client\Common\Plugin;
use WPSentry\ScopedVendor\Http\Client\Exception\HttpException;
use WPSentry\ScopedVendor\Http\Promise\Promise;
use WPSentry\ScopedVendor\Psr\Http\Client\ClientExceptionInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface;
use WPSentry\ScopedVendor\Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * Retry the request if an exception is thrown.
 *
 * By default will retry only one time.
 *
 * @author Joel Wurtz <joel.wurtz@gmail.com>
 */
final class RetryPlugin implements \WPSentry\ScopedVendor\Http\Client\Common\Plugin
{
    /**
     * Number of retry before sending an exception.
     *
     * @var int
     */
    private $retry;
    /**
     * @var callable
     */
    private $errorResponseDelay;
    /**
     * @var callable
     */
    private $errorResponseDecider;
    /**
     * @var callable
     */
    private $exceptionDecider;
    /**
     * @var callable
     */
    private $exceptionDelay;
    /**
     * Store the retry counter for each request.
     *
     * @var array
     */
    private $retryStorage = [];
    /**
     * @param array $config {
     *
     *     @var int $retries Number of retries to attempt if an exception occurs before letting the exception bubble up
     *     @var callable $error_response_decider A callback that gets a request and response to decide whether the request should be retried
     *     @var callable $exception_decider A callback that gets a request and an exception to decide after a failure whether the request should be retried
     *     @var callable $error_response_delay A callback that gets a request and response and the current number of retries and returns how many microseconds we should wait before trying again
     *     @var callable $exception_delay A callback that gets a request, an exception and the current number of retries and returns how many microseconds we should wait before trying again
     * }
     */
    public function __construct(array $config = [])
    {
        $resolver = new \WPSentry\ScopedVendor\Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults(['retries' => 1, 'error_response_decider' => function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface $response) {
            // do not retry client errors
            return $response->getStatusCode() >= 500 && $response->getStatusCode() < 600;
        }, 'exception_decider' => function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, \WPSentry\ScopedVendor\Psr\Http\Client\ClientExceptionInterface $e) {
            // do not retry client errors
            return !$e instanceof \WPSentry\ScopedVendor\Http\Client\Exception\HttpException || $e->getCode() >= 500 && $e->getCode() < 600;
        }, 'error_response_delay' => __CLASS__ . '::defaultErrorResponseDelay', 'exception_delay' => __CLASS__ . '::defaultExceptionDelay']);
        $resolver->setAllowedTypes('retries', 'int');
        $resolver->setAllowedTypes('error_response_decider', 'callable');
        $resolver->setAllowedTypes('exception_decider', 'callable');
        $resolver->setAllowedTypes('error_response_delay', 'callable');
        $resolver->setAllowedTypes('exception_delay', 'callable');
        $options = $resolver->resolve($config);
        $this->retry = $options['retries'];
        $this->errorResponseDecider = $options['error_response_decider'];
        $this->errorResponseDelay = $options['error_response_delay'];
        $this->exceptionDecider = $options['exception_decider'];
        $this->exceptionDelay = $options['exception_delay'];
    }
    /**
     * {@inheritdoc}
     */
    public function handleRequest(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, callable $next, callable $first) : \WPSentry\ScopedVendor\Http\Promise\Promise
    {
        $chainIdentifier = \spl_object_hash((object) $first);
        return $next($request)->then(function (\WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface $response) use($request, $next, $first, $chainIdentifier) {
            if (!\array_key_exists($chainIdentifier, $this->retryStorage)) {
                $this->retryStorage[$chainIdentifier] = 0;
            }
            if ($this->retryStorage[$chainIdentifier] >= $this->retry) {
                unset($this->retryStorage[$chainIdentifier]);
                return $response;
            }
            if (\call_user_func($this->errorResponseDecider, $request, $response)) {
                $time = \call_user_func($this->errorResponseDelay, $request, $response, $this->retryStorage[$chainIdentifier]);
                $response = $this->retry($request, $next, $first, $chainIdentifier, $time);
            }
            if (\array_key_exists($chainIdentifier, $this->retryStorage)) {
                unset($this->retryStorage[$chainIdentifier]);
            }
            return $response;
        }, function (\WPSentry\ScopedVendor\Psr\Http\Client\ClientExceptionInterface $exception) use($request, $next, $first, $chainIdentifier) {
            if (!\array_key_exists($chainIdentifier, $this->retryStorage)) {
                $this->retryStorage[$chainIdentifier] = 0;
            }
            if ($this->retryStorage[$chainIdentifier] >= $this->retry) {
                unset($this->retryStorage[$chainIdentifier]);
                throw $exception;
            }
            if (!\call_user_func($this->exceptionDecider, $request, $exception)) {
                throw $exception;
            }
            $time = \call_user_func($this->exceptionDelay, $request, $exception, $this->retryStorage[$chainIdentifier]);
            return $this->retry($request, $next, $first, $chainIdentifier, $time);
        });
    }
    /**
     * @param int $retries The number of retries we made before. First time this get called it will be 0.
     */
    public static function defaultErrorResponseDelay(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface $response, int $retries) : int
    {
        return \pow(2, $retries) * 500000;
    }
    /**
     * @param int $retries The number of retries we made before. First time this get called it will be 0.
     */
    public static function defaultExceptionDelay(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, \WPSentry\ScopedVendor\Psr\Http\Client\ClientExceptionInterface $e, int $retries) : int
    {
        return \pow(2, $retries) * 500000;
    }
    /**
     * @throws \Exception if retrying returns a failed promise
     */
    private function retry(\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, callable $next, callable $first, string $chainIdentifier, int $delay) : \WPSentry\ScopedVendor\Psr\Http\Message\ResponseInterface
    {
        \usleep($delay);
        // Retry synchronously
        ++$this->retryStorage[$chainIdentifier];
        $promise = $this->handleRequest($request, $next, $first);
        return $promise->wait();
    }
}
