<?php

namespace App\Roles;


trait Roles
{
	public function hasRole($role)
    {
        $primary_permissions = json_decode( $this->roles()->first()->permissions , true ) ?? [];

        $secondary_permissions = json_decode( $this->permissions , true) ??[];

        foreach ($primary_permissions as $permission => $value) {

            if ( (str_is($role, $permission) || str_is($permission , $role) ) and $value == true) {
                return true;
            }

        }

        foreach ($secondary_permissions as $permission  => $value) {
                
            if ( ( str_is($role, $permission) || str_is($permission , $role) ) and $value == true ) {

                return true;
            }

        }

        return false;
    }


	public function updatePermissions($id ,$permission,$value,$action=false)
    {   
        $user = $this->getUser($id);

        $permissions = json_decode( $user->permissions , true) ??[];

        if ( array_key_exists($permission, $permissions) ) {
            $permissions[$permission] = $value;

            return $user->setPermissions($user , $permissions);

        }elseif ( $action == true ) {

            $user->addPermissions($id , $permission , $value);

        }
    }

    public function setPermissions($user , $permissions)
    {	

        $user->permissions = json_encode($permissions);

        $user->save();

        return true;
    }

    public function addPermissions($id , $permission , $value)
    {   
        $user = $this->getUser($id);

        $permissions = json_decode( $user->permissions , true) ??[];

        
        if ( is_string($permission) ) {
          
           if (! array_key_exists($permission, $permissions)) {
            
                $permissions[$permission] = $value;

                $user->setPermissions($user , $permissions);

                return true;
            }

        }elseif ( is_array($permission) ) {

            foreach ($permission as $key => $value) {

                if (! $user->hasRole($key) ) {
                    
                    $permissions[$key] = $value;

                }
            }

            return $user->setPermissions($user , $permissions);
        }

        return false;
    }

    public function removePermissions($id , $ermission)
    {
    	$user = $this->getUser($id);

    	$permissions = json_decode($user->permissions , true ) ??[];

    	if ( is_string($permission) ) {
    		
    		if ( array_key_exists($permission, $permissions) ) {
    			unset($permissions[$permission]);
    		}else{

    			return response("Permission $permission Does not Exists" , 200);

    		}

    	}

    	if (is_array($permission) ) {
    		
    		if ( count($permission) == 0 ) {
    			return response('Write Permission To Be Removed' , 200);
    		}else {
    			
    			foreach ($permission as $key => $value) {
    				if ( ! isset($permissions[$key]) ) {
    			
						return response("$value permission Does not Exists", 200);
    				}else{
    					unset($permissions[$key]);		
    				}

       			}

       		$user->setPermissions($user , $permissions);

    		}
    	}
	}

}