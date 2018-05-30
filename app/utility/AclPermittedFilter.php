<?php

class AclPermittedFilter {
    public function filter($route, $request)
    {
        $permitted = false;
        
        $user = Auth::admin()->get();
        
        $mapping_route = $route->getName();
        $route_name = $route->getName();
        $route_arr = explode('.', $route_name);
        $route_arr_last = $route_arr[sizeof($route_arr) - 1];

        if($route_arr_last == 'store')
        {
            $route_arr[sizeof($route_arr) - 1] = 'create';
            $mapping_route = implode('.', $route_arr);
        }

        if($route_arr_last == 'update')
        {
            $route_arr[sizeof($route_arr) - 1] = 'edit';
            $mapping_route = implode('.', $route_arr);
        }

        if($route_arr_last == 'destroy')
        {
            $route_arr[sizeof($route_arr) - 1] = 'delete';
            $mapping_route = implode('.', $route_arr);
        }

        $user->load('groups');

        foreach($user->groups as $group) {
            $group->load('permissions');
            if($group->permissions->find($mapping_route)) {
                $permitted = true;
                break;
            }
        }

        if(!$permitted) {

            if($request->ajax()){
                return Response::json(array('message' => 'Page You are try to access is not accessible for you. ','success'=>false), 403);
            }

            return Redirect::route('admin.denied');
        }
    }
}