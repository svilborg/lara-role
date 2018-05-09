<?php
namespace App\Http\Middleware;

use Closure;

class Role
{

    private $name = "acl";

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userRole = "";

        if ($request->user()) {
            $userRole = $request->user()->role;
        }

        $action = $request->route()->getAction();

        // If AcL applied to action
        if (isset($action[$this->name])) {

            if (! in_array($userRole, $action[$this->name]['role'])) {
                return abort(401, 'Role not authorized to access resource.');
            }
        }

        return $next($request);
    }
}
