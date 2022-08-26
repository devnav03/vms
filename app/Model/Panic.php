<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Panic extends Model

{

//   use SoftDeletes;



   public function OfficerDetail(){

        return $this->hasOne('\App\Admin'::class, 'id', 'officer_id');   

    }

    

   public function officerDepartment(){

        return $this->hasOne('\App\Admin'::class, 'id', 'department_id');   

    }


    
   public function getVisitor(){

        return $this->hasOne('\App\User'::class, 'id', 'visitor_id');   

    }

}