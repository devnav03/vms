<?php
namespace App\Http\Controllers\Admin;

use App\Model\PanicAlert;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
class PanicAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("vivek");
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $menus = PanicAlert::where($cidata_ob->where,$cidata_ob->cid)->get();
        return view('admin.panic-alert.list',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.panic-alert.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $user_id =  auth('admin')->user()->id;
        $this->validate($request,[
            'name' => 'required',
            'mobile'=>'required|numeric|digits:10',
            'email' => 'required|email',
        ]);

        $panic = new PanicAlert;
        $panic->name = $request->name;
        $panic->mobile = $request->mobile;
        $panic->email = $request->email;
        $panic->officer_id = $user_id;
        $panic->status = 1;
        $column=$cidata_ob->where;
        $panic->$column = $cidata_ob->cid;
        $panic->save();

        return redirect()->back()->with(['class'=>'success','message'=>'Panic Alert added successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($table)
    {
        $menus = PanicAlert::where('id', $table)->first();
        return view('admin.panic-alert.edit',compact('menus','table'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $table)
    {
        $this->validate($request,[
            'name' => 'required',
            'mobile'=>'required|numeric|digits:10',
            'email' => 'required|email',
        ]);
        $panic_update = PanicAlert::where('id', $table)->first();
        $panic_update->name = $request->name;
        $panic_update->mobile = $request->mobile;
        $panic_update->email = $request->email;

        if($panic_update->save()){
            return redirect()->back()->with(['class'=>'success','message'=>'Panic Alert Update successfully']);
        }else{
            return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($table)
    {
        $menu = PanicAlert::where('id',$table);
        if ($menu->count()) {
            $menu->delete();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Panic Alert deleted successfully']);

    }

}
