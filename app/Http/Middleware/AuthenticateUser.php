<?php
/**
 * @author Mike Alvarez <mike@hallohallo.ph>
 *
 * @note this only supports one guard key anything after the
 * 3rd index of the function argument will be considered as type
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class AuthenticateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @param  string|null               $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! Auth::guard($guard)->check()) {
            throw new AuthenticationException('Unauthenticated.', [$guard]);
        }

        if (! $user = Auth::guard($guard)->user()) {
            return $this->createResponse($request, trans('api.resource_unauthorized'), 401);
        }

        // checks the type of the user
        if (func_num_args() > 3) {
            $types = array_slice(func_get_args(), 3);
            if (! in_array($user->type, $types)) {
                return $this->createResponse($request, trans('api.resource_forbidden'), 403);
            }
        }

        return $next($request);
    }

    /**
     * creates the response object
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $message
     * @param  int                      $status
     *
     * @return \Illuminate\Http\Response
     */
    protected function createResponse($request, $message = null, $status = 401)
    {
        if ($request->expectsJson()) {
            return response()->json(
                ['message' => $message], $status
            );
        }

        return redirect()->guest(route('login'));
    }
}
