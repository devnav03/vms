<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{



      /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $array = ['browse'=>'index','read'=>'show','add'=>'create','adds'=>'store','edits'=>'edit','edit'=>'update','delete'=>'destroy'];
        $pages =  (str_singular(array_search(last(explode('.', request()->route()->action['as'])), $array)).'_'.str_plural(str_singular(str_replace('-', '_', request()->segment(2))),2));
        $page = \App\Model\Permission::where('key',$pages)->whereHas('permission',function($query){
            $query->where('role_id',auth('admin')->user()->role_id);
        })->get();

        if($page->count()){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        // dd(auth('admin')->user()->role->permissions);
        return [
            //
        ];
    }
}
