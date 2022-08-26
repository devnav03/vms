<?php







namespace App\Http\Controllers\Admin;







use App\Model\Role;



use App\Model\Permission;



use App\Model\PermissionRole;



use App\Http\Requests\Admin\Request;



use App\Http\Controllers\Controller;







class RoleController extends Controller



{



    /**



     * Display a listing of the resource.



     *



     * @return \Illuminate\Http\Response



     */



    public function index(Request $request)



    {

        // $cidata=$request->session()->get('CIDATA');
        // $cidata_ob=json_decode($cidata);
        // $company_id=$cidata_ob->cid;

        $roles = Role::all();



        return view('admin.role.list', compact('roles'));
    }







    /**



     * Show the form for creating a new resource.



     *



     * @return \Illuminate\Http\Response



     */



    public function create(Request $request)



    {



        return view('admin.role.create');
    }







    /**



     * Store a newly created resource in storage.



     *



     * @param  \Illuminate\Http\Request  $request



     * @return \Illuminate\Http\Response



     */



    public function store(Request $request)



    {



        $this->validate($request, ['role_name' => 'required']);


        
        $role = new Role;



        $role->name = $request->role_name;


        if ($role->save()) {



            return redirect()->back()->with(['class' => 'success', 'message' => 'Role Created Successfully..']);
        }



        return redirect()->back()->with(['class' => 'error', 'message' => 'Error in role creating... ']);
    }







    /**



     * Display the specified resource.



     *



     * @param  int  $id



     * @return \Illuminate\Http\Response



     */



    public function show(Request $request, Role $role)



    {



        return view('admin.role.show', compact('role'));
    }







    /**



     * Show the form for editing the specified resource.



     *



     * @param  int  $id



     * @return \Illuminate\Http\Response



     */



    public function edit(Request $request,$role_id)

    {
   
        $cidata=$request->session()->get('CIDATA');
	    $cidata_ob=json_decode($cidata);
        $role_permission=PermissionRole::where(['role_id'=>$role_id,'company_id'=>$cidata_ob->cid])->select('permission_id')->get();

        $permissions = Permission::has('menu')->get()->groupBy('table_name');



        return view('admin.role.edit', compact('role_permission', 'permissions','role_id'));
    }







    /**



     * Update the specified resource in storage.



     *



     * @param  \Illuminate\Http\Request  $request



     * @param  int  $id



     * @return \Illuminate\Http\Response



     */



    public function update(Request $request, $role_id)



    {
        // echo $role_id;die;





        $cidata=$request->session()->get('CIDATA');
	    $cidata_ob=json_decode($cidata);
        PermissionRole::where(['role_id' => $role_id,'company_id'=>$cidata_ob->cid])->delete();







        if (request('permissions')) {



            foreach (request('permissions') as $key => $value) {



                $permission = new PermissionRole;



                $permission->role_id = $role_id;



                $permission->permission_id = $value;
                $permission->company_id = $cidata_ob->cid;



                $permission->save();
          
            }
        }


       
        return redirect()->back()->with(['class' => 'success', 'message' => 'Permission alined success']);
    }







    /**



     * Remove the specified resource from storage.



     *



     * @param  int  $id



     * @return \Illuminate\Http\Response



     */



    public function destroy(Request $request, $id)



    {



        //



    }
}
