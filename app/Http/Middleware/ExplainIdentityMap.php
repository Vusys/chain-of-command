<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Vusys\QuantumSlipstreamDrive\IdentityMap;

/**
 * Puts what slipstream did for this request into the page footer: how many
 * queries ran, and how many it answered from memory instead.
 */
class ExplainIdentityMap
{
    public const string MARKER = '<!--identity-map-->';

    public function handle(Request $request, Closure $next): Response
    {
        $queries = 0;
        DB::listen(function () use (&$queries): void {
            $queries++;
        });

        $response = null;
        $explanations = IdentityMap::explain(function () use (&$response, $request, $next): void {
            $response = $next($request);
        });

        $fromMemory = count(array_filter($explanations, fn ($explanation): bool => ! $explanation->sqlExecuted));
        $content = $response->getContent();
        if (is_string($content) && str_contains($content, self::MARKER)) {
            $response->setContent(str_replace(
                self::MARKER,
                sprintf('<span data-queries="%d" data-from-memory="%d">%d queries · %d answered from memory</span>', $queries, $fromMemory, $queries, $fromMemory),
                $content,
            ));
        }

        return $response;
    }
}
