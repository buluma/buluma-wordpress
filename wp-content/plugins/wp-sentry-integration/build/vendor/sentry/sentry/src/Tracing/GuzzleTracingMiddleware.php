<?php

declare (strict_types=1);
namespace Sentry\Tracing;

use WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface;
use Sentry\SentrySdk;
use Sentry\State\HubInterface;
/**
 * This handler traces each outgoing HTTP request by recording performance data.
 */
final class GuzzleTracingMiddleware
{
    public static function trace(?\Sentry\State\HubInterface $hub = null) : \Closure
    {
        return function (callable $handler) use($hub) : \Closure {
            return function (\WPSentry\ScopedVendor\Psr\Http\Message\RequestInterface $request, array $options) use($hub, $handler) {
                $hub = $hub ?? \Sentry\SentrySdk::getCurrentHub();
                $span = $hub->getSpan();
                $childSpan = null;
                if (null !== $span) {
                    $spanContext = new \Sentry\Tracing\SpanContext();
                    $spanContext->setOp('http.guzzle');
                    $spanContext->setDescription($request->getMethod() . ' ' . $request->getUri());
                    $childSpan = $span->startChild($spanContext);
                }
                $handlerPromiseCallback = static function ($responseOrException) use($childSpan) {
                    if (null !== $childSpan) {
                        $childSpan->finish();
                    }
                    if ($responseOrException instanceof \Throwable) {
                        throw $responseOrException;
                    }
                    return $responseOrException;
                };
                return $handler($request, $options)->then($handlerPromiseCallback, $handlerPromiseCallback);
            };
        };
    }
}
