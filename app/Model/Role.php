<?php



namespace App\Model;



use Illuminate\Database\Eloquent\Model;



class Role extends Model

{

    protected $fillable = [

        'name', 'slug', 'permissions',

    ];

    protected $casts = [

        'permissions' => 'array',

    ];



    protected $hidden = [

        'deleted_at','updated_at','created_at'

    ];



    public function admins()

    {

        return $this->hasMany(\App\Admin::class);

    }



    public function hasAccess(string $permissions) :bool

    {

        if ($this->hasPermission($permissions)){

            return true;

        }

        return false;

    }

    public function users()

    {

        return $this->belongsToMany(User::class, 'role_users');

    }



    private function hasPermission(string $permission) 

    {

        return $this->permissions->pluck('permission.id','permission.key')->has($permission);

    }



    // public function permissions(){

    //     return $this->hasMany(PermissionRole::class)->select('permission_id');

    // }

}

