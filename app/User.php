<?php

namespace App;

use App\Roles\roles;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable , roles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('\App\Role','user_roles' , 'user_id' , 'role_id');
    }

    public static function listUser()
    {
        foreach (self::pluck('id') as $id) {
            
            $user = self::whereId($id)->first();

            if (! $user->hasRole('admin.*') ) {
                $users[] = $user;
            }
        }

        return $users ?? NULL;
    }


    public function upgradeOrDowngradeUser($id = null, $permissions)
    {   
        $id = $id ?? $this->id;

        $user = $this->getUser($id);


        if ($user->hasRole('admin.*') ) {
            return false;
        }

        foreach ($permissions as $key => $value) {

            $user->updatePermissions($id , $key , $value , true);
        }

        return true;
    }

    

    public function getUser($id)
    {
        return self::whereId($id)->first();
    }

}
