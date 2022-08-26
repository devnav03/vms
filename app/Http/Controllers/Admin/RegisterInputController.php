<?php
namespace App\Http\Controllers\Admin;

use App\Model\RegisterInput;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use URL;

class RegisterInputController extends Controller
{
	
	protected $controller;
	public function __construct( Controller $controller ){
		$this->controller = $controller;
	}


    public function index(Request $request) {

        $data= RegisterInput::where('id', 1)->first();

        return view('admin.register_input.list',compact('data'));
    }
    

    public function input_field($id = null){

        $result = RegisterInput::where('id', 1)->select($id)->first();

        if($result->$id == 1)
        $value = 0;
        else
        $value = 1;

        RegisterInput::where('id',  1)
       ->update([
           $id => $value
        ]);

        return back();

    }

    public function show($table)
    {

    }

}
