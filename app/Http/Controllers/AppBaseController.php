<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 *   @OA\Info(
 *     title="Jolli API Documentation",
 *     version="1.0.0",
 *     description="## Jolli API
 Jolli is a utility service for creating Jollirable moments for individual and corporate users worldwide.
 
 Jolli offers:
 
 - Me Hour
 - Tasks/Reminders
 - Notes
 - Smart Goal Set/Tracking
 - Next Generation Audio Chat
 - Jolliverse
 - Gamified/Point System

 ## Including relations
 To include relations when creating/updating models, you can simply append the array in the payload. e.g.

     POST /api/v1/users
     {
         ""first_name"": ""Peter"",
         ""last_name"": ""Obi"",
         ""email"": ""peter@gmail.com"",
         ""address"" [
             ""country_id"": 38,
             ""state_id"": 2000,
             ""city_id"": 16240,
             ""street"": ""14 Becker Street, Myanar"",
             ""post_code"": ""30800""
         ]
     }
 ## Including Resources 
 For all api resources, the index endpoint contains a documentation of the available include for each resource.
 
 The format for requesting data to be included with data input is as follows:

    /api/v1/endpoint?include=csv

 Where csv is basically a comma separated list of relations to include. e.g.:

    /api/v1/users/me?include=role,companies

 Nested includes are also supported, say for example we want to retrieve the user who created a company as well:

    /api/v1/users/me?include=role,companies.user

 ## Contacts
 Opata Chibueze (Lead Developer) - opatachibueze@gmail.com"
 *   ),
 * )
 * @OA\SecurityScheme(
 *   securityScheme="Bearer",
 *   scheme="bearer",
 *   type="http",
 *   description="You can obtain this from [Login Endpoint](/api/documentation/#Login)",
 *   in="header",
 *   bearerFormat="JWT"
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function sendResponse($result, $message, $code = 200, $paginate = true)
    {
        if (is_array($result)) {
            return response()->json(ResponseUtil::makeResponse($message, $result), $code);
        }

        $class = $result;
        $is_collect = false;
        //try to get resource transformer from response and return;
        $is_paginate = false;
        if ($this->isCollection($result) && ! $result->isEmpty()) {
            $class = $result->first();
            $is_collect = true;
            //custom pagination
            if ($paginate === true) {
                $result = $this->manual_paginate($result);
                $is_paginate = true;
            }
        } elseif ($this->isPagination($result) && ! $result->isEmpty()) {
            $class = $result->first();
            $is_paginate = true;
        }

        $class = is_object($class) ? get_class($class) : $class;

        $transformClass= "App\\Http\\Resources".str_replace(array("App\\Models"), array(""), $class)."Resource";
        
        if (class_exists($transformClass)) {
            if ($is_paginate || $is_collect) {
                return $this->sendResponse($transformClass::collection($result), $message, $code);
            }
            return $this->sendResponse(new $transformClass($result), $message, $code);
        }

        return response()->json(ResponseUtil::makeResponse($message, $result), $code);
    }

    /**
     * Format a response for sending to client
     */
    public function formatResponse($result, $message)
    {
        return $result->addMeta('success', true)->addMeta('message', $message);
    }

    /**
     * Sends a raw message back to client
     */
    public function sendSuccess($message, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], $code);
    }

    /**
     * Send formatted error to client
     */
    public function sendError($error, $code = 422)
    {
        return response()->json(ResponseUtil::makeError($error), $code);
    }

    /**
     * Send formatted error to client
     */
    public function sendMissing($error, $code = 404)
    {
        return $this->sendError($error, $code);
    }

    /**
     * Determine if the instance is a collection.
     *
     * @param object $instance
     *
     * @return bool
     */
    protected function isCollection($instance)
    {
        return $instance instanceof Collection;
    }

    /**
     * Determine if the instance is a paginator.
     *
     * @param object $instance
     *
     * @return bool
     */
    protected function isPagination($instance)
    {
        return $instance instanceof Paginator;
    }

    /**
    * Checks if particluar role is the type logged in
    * @param  int $role Role to check for
    * @return boolean $abort Specifies if the app should abort on role check fail
    */
    public function checkRole(string $role, $abort = true)
    {
        if (auth('api')->user()) {
            if (strtolower(auth('api')->user()->role->id) === $role) {
                return true;
            }
        }
        if ($abort) {
            throw new AccessDeniedHttpException(__('auth.no_permissions'));
        }
        return false;
    }

    /**
    * Checks if the current API user has permissions to access this resource
    * @param integer $user_id The actual user who has access to this resource
    */
    public function checkOwner($user_id)
    {
        if(app()->environment() == 'testing') return true;
        if (auth('api')->user()->id != $user_id) {
            throw new AccessDeniedHttpException(__('auth.no_permissions'));
        }
    }

    /**
    * Checks if the current API user has permissions to access this resource
    * @param integer $user_id The actual user who has access to this resource
    */
    public function checkCompanyOrUser($object, $abort = true)
    {
        if(app()->environment() == 'testing') return true;
        $user = auth('api')->user() ?? auth('web')->user();
        if ($user->role_id == config('constants.roles.super_admin')) return true;
        if (property_exists($object, 'company_id')) {
            if (!$user->company) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
            if ($user->company->id !== $object->company_id) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
        } else {            
            if (auth('api')->user()->id !== $object->user_id) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            }
        }
    }

    public function autoCheck($object, $abort = true)
    {
        if(app()->environment() == 'testing') return true;
        $user = auth('api')->user() ?? auth('web')->user();
        if (!$user) {
            if ($abort) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            } return false;
        }
        if ($user->role_id == config('constants.roles.super_admin')) return true;
        if (property_exists($object, 'vendor_id')) {
            if (!$user->vendor) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
            if ($user->vendor->id !== $object->vendor_id) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
        }
        if (property_exists($object, 'company_id')) {
            if (!$user->company) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
            if ($user->company->id !== $object->company_id) {
                if ($abort) {
                    throw new AccessDeniedHttpException(__('auth.no_permissions'));
                } return false;
            }
        }

        return true;
    }

    public function checkVendor($object, $abort = true)
    {
        if(app()->environment() == 'testing') return true;
        $user = auth('api')->user() ?? auth('web')->user();
        if (!$user) {
            if ($abort) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            } return false;
        }
        if ($user->role_id == config('constants.roles.super_admin')) return true;
        if (!$user->vendor) {
            if ($abort) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            } return false;
        }
        if ($user->vendor->id !== $object->vendor_id) {
            if ($abort) {
                throw new AccessDeniedHttpException(__('auth.no_permissions'));
            } return false;
        }
        return true;
    }


    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($query, $perPage = null, $columns = ['*'])
    {
        if (!$perPage) {
            $perPage = config('constants.default_pagination');
        }
        return $query->paginate(request()->get('per_page', $perPage), $columns);
    }

    /**
     * Collection paginator
     *
     * @param $items The items to paginate
     * @param $perPage Number of items per page
     * @param $page The current page
     * @param $pageName The string to use as page query string
     *
     * @return LengthAwarePaginator
     */
    public function manual_paginate($items, $perPage = null, $page = null, $pageName = 'page')
    {
        if ($perPage == null) {
            $perPage = request()->get('per_page', config('constants.default_pagination'));
        }
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
        $items    = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    /**
     * Performs a field value search on the specified model and paginatest the result
     */
    public function model_search($model, $fields, $values)
    {
        $modelName = str_replace(["App\\Models"], [""], get_class($model));

        if (!is_array($fields)) {
            $fields = [$fields];
        }
        if (!is_array($values)) {
            $values = [$values];
        }

        foreach ($fields as $f) {
            if (!$model->isSearchable($f)) {
                throw new \Exception(sprintf("Disallowed search parameter < %s > provided for filter", $f));
            }
        }

        $results = $model;

        for ($i = 0; $i < count($fields); $i++) {
            $f = $fields[$i];
            $v = $values[$i];
            $results = $results->byFieldLike($f, $v);
        }
        
        $results = $results->paginate(request()->get('per_page', config('constants.default_pagination')));
        
        return $this->sendResponse($results, $modelName.'s filtered successfully');
    }
}
