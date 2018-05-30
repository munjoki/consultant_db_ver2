<?php

class CheckPermission
{
	public static function isPermitted($routeName)
	{
		$permitted = false;

		$user = Auth::admin()->get();
        $user->load('groups');

        foreach($user->groups as $group) {
            $group->load('permissions');
            if($group->permissions->find($routeName)) {
                $permitted = true;
            }
        }

        return $permitted;
	}
}