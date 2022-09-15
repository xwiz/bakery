<?php

namespace App\Http\Middleware;

use App\Repositories\VendorRepository;
use Closure;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PermissionMiddleware
{
    /** VendorRepository */
    private $vendorRepository;

    public function __construct(VendorRepository $repo)
    {
        $this->vendorRepository = $repo;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->user()) {
            $user = auth()->user();
            $role = $user->role;

            if (!$role) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            }
            $request->_user = $user;
            $vendor = $user->vendor;

            if ($vendor) {
                $request->_vendor = $vendor;
            }
        } else {
            throw new AccessDeniedHttpException(__('auth.invalid_token'));
        }

        return $next($request);
    }
}
