<?php
namespace App\Http\Controllers\Admin;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Requests\Admin\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menus = Menu::whereNull('parent_id')->orderBy('order')->with(['childs'=>function($query){
            $query->orderBy('order');
        }])->get();
        return view('admin.menu.list', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        return view('admin.menu.create');
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
            'menu_name' => 'required|unique:menus,title',
            'status' => 'required',
        ]);
        $menu = Menu::firstOrNew(['title'=>$request->menu_name]);
        $menu->title = $request->menu_name;
        $menu->status = $request->status;
        $menu->icon = $request->icon;
        if($menu->save()){
            return redirect()->to(adminRoute('index'))->with(['class'=>'success','message'=>'Menu created successfully']);
        }
        return redirect()->back()->with(['class'=>'error','message'=>'something went wrong !']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return view('admin.menu.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $menu = Menu::where('id', $id)->first();
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        // return response()->json($request->all(),500);
        if($request->_list){
            foreach ($request->data as $key=>$menu) {
               $parentMenu = Menu::select(['id', 'order','parent_id'])->where('id', $menu['id'])->first();
               $parentMenu->parent_id = NULL;
               $parentMenu->order = $key;
               $parentMenu->save();
               if(isset($menu['children'])) {
                    foreach($menu['children'] as $key2 =>$childs) {
                        $childMenu = Menu::select('id', 'order', 'parent_id')->where('id', $childs['id'])->first();
                        $childMenu->order = $key2;
                        $childMenu->parent_id = $parentMenu->id;
                        $childMenu->save();
                    }
               }
            }
            return response()->json(array('error'=>false,'message'=>['Menu updated successfully']));
        }
   
        $menu = Menu::firstorNew(['id'=>$request->id]);
        $menu->title = $request->menu_name;
        $menu->icon = $request->icon;
        $menu->status = $request->status;
        if($menu->save()){
            return redirect()->to(adminRoute('index'))->with(['class'=>'success','message'=>'Menu updated successfully']);
        }
        return redirect()->back()->with(['class'=>'error','message'=>'Something went wrong !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::select('id', 'parent_id')->with(['childs'])->where('id', $id)->first();
        if($menu->childs->count()){
            return response()->json(['class'=>'success', 'message'=>'You need to delete child first.']);
        }else{
            $permission = Permission::where('menu_id', $menu->id)->get();
            foreach($permission as $value){
                PermissionRole::where('permission_id', $value->id)->delete();
                $value->delete();
            }
            if($menu->delete()){
                return response()->json(['class'=>'success', 'message'=>'Menu deleted successfully']);
            }
            return response()->json(['class'=>'error', 'message'=>'Something went wrong !'], 500);
            
        }
    }
}