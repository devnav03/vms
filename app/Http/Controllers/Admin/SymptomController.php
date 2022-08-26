<?php
namespace App\Http\Controllers\Admin;

use App\Model\Symptom;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd("vivek");
        $menus = Symptom::get();
        return view('admin.symptoms.list',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.symptoms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);
        
        $symptom = new Symptom;
        $symptom->name = $request->name;
        $symptom->status = 1;
        $symptom->save();
        
        return redirect()->back()->with(['class'=>'success','message'=>'Symptom added successfully']);
       
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
        $menus = Symptom::where('id', $table)->first();
        return view('admin.symptoms.edit',compact('menus','table'));
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
        ]);
        $sys_update = Symptom::where('id', $table)->first();
        $sys_update->name = $request->name;
        
        if($sys_update->save()){
            return redirect()->back()->with(['class'=>'success','message'=>'Symptom Update successfully']);
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
        $menu = Symptom::where('id',$table);
        if ($menu->count()) {
            $menu->delete();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Symptom deleted successfully']);
        
    }

}
