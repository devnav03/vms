<?php
namespace App\Http\Controllers\Admin;
use App\Model\Panic;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
class PanicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $menus = Panic::with(['OfficerDetail'=>function($query){
              $query->select('id','name','department_id','mobile','email')->with(['getDepart'=>function($query){
                $query->select('id','name');
             }]);
         }])->where($cidata_ob->where,$cidata_ob->cid)->get();
    // dd($menus);
        return view('admin.panic.list',compact('menus'));
    }

    


}
