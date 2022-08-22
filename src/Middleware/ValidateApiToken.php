<?php

namespace Vollborn\LaravelApiTokens\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Vollborn\LaravelApiTokens\Models\ApiToken;
use function abort;
use function gettype;
use function now;

class ValidateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     * @noinspection PhpClassConstantAccessedViaChildClassInspection
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $header = $request->header('Authorization');
        $prefix = 'Bearer ';

        if (!$header || gettype($header) !== 'string' || !Str::startsWith($header, $prefix)) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $token = Str::after($header, $prefix);

        $apiToken = ApiToken::query()
            ->with('user')
            ->whereDate('expires_at', '>', now())
            ->where('token', $token)
            ->first();

        if (!$apiToken || !$apiToken->user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        Auth::login($apiToken->user);

        return $next($request);
    }
}
