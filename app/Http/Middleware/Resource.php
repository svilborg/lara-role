<?php
namespace App\Http\Middleware;

use Closure;

class Resource
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
        $userResources = [];

        $action = $request->route()->getAction();

        // If ACL applied to action
        if (isset($action[$this->name])) {

            if ($request->user()) {
                $userResources = $request->user()->resources;
            }

            // $actionName = $request->route()->getActionName();
            $controller = $this->getControllerName($request);
            $action = $this->getActionName($request);

            if (! isset($userResources[$controller]) || ! in_array($action, $userResources[$controller])) {
                return abort(401, 'User not authorized to access resource.');
            }
        }

        return $next($request);
    }

    /**
     * Get Controller Name
     *
     * @return string
     */
    protected function getControllerName($request)
    {
        $action = $request->route()->getActionName();

        $name = preg_match('/@/is', $action) ? explode('@', $action)[0] : $action;
        $name = last(explode("\\", $name));
        $name = str_replace('controller', '', strtolower($name));

        return $name;
    }

    /**
     * Get Controller Name
     *
     * @return string
     */
    protected function getActionName($request)
    {
        $action = $request->route()->getActionName();

        if (preg_match('/@/is', $action)) {
            $name = explode('@', $action)[1];
            return $name;
        }

        return "";
    }
}
