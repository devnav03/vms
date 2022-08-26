<?php



namespace App\Http\Controllers\Admin;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;



use App\Model\Category;

use App\Model\UserCart;



class CategoryController extends Controller

{

   public function index(Request $request)

	{    
        $category = Category::select('id','name','description','image','parent_id','created_at','sort_order','status')->get();

      if($request->ajax()){



            $datas = Category::select('id','name','description','image','parent_id','created_at','sort_order','status');



            $totaldata = $datas->count();

             $search = $request->search['value'];

            if($search) {

                $datas->where('name', 'like', '%'.$search.'%')

                    ->orWhere('description', 'like', '%'.$search.'%');

            }

           



            # set datatable parameter 

            $result["length"]           = $request->length;

            $result["recordsTotal"]     = $totaldata;

            $result["recordsFiltered"]  = $datas->count();

            $records                    = $datas->limit($request->length)->offset($request->start)->get();

            # fetch table records 

            $result['data']     = [];           

            foreach ($records as $count=>$data) {

             $result['data'][] =[

                'id'                =>$data->id,

                'sn'                =>++$request->start,

                'name'              =>$data->name,

                'status'            => @$data->status==1?'Active':'Deactive', 

                'sort_order'        =>$data->sort_order,       

                'image'             =>asset('/storage/'.$data->image.''), 

                'description'       =>$data->description,

                'created_at'        =>$data->created_at->toDateTimeString(), 

                'edit'              =>'Edit'];

           

            }

            return $result;

        }



      return view('admin.category.list', compact('category'));



	}

     public function show(Request $request){



          $category = Category::select('id','name','description')->get();



        return view('admin.category.show',compact('category'));

    }



    public function create(Request $request){



          $all_category = Category::select('id','name')->get();       



    	return view('admin.category.create',compact('all_category'));

    }



    public function edit(Request $request, $cate_id){



        $all_category = Category::select('id','name','sort_order','status')->get();

        $all_status=[ 

            array('name'=>'Active','id'=>1),

            array('name'=>'Deactive','id'=>0),

        ];

    	$category = Category::where('id', $cate_id)->first();





    	return view('admin.category.edit', compact('category','all_category','all_status'));

    }



   



    public function store(Request $request){



    	$this->validate($request, [

                'name'          =>'required',

                'description'   =>'required',

                'sort_order'    =>'required',

                'parent_id'     =>'required',

                'category_image'=>'required|file|max:10000',

            ]);





        $category = new Category();

        $category->name        = $request->name;

        $category->description = $request->description;

        $category->status      = 1;

        $category->sort_order  =$request->sort_order;

        $category->parent_id   = $request->parent_id;

        $category->image =  $request->file('category_image')->store('category');

        $category->save();

        

        return redirect('admin-panel/categories/')->with(['class'=>'success', 'message'=>'Category Successfully Added!']);



    }



    public function update(Request $request, $cate_id){





    	$this->validate($request, [

                'name'          =>'required',

                'description'   =>'required',

                'sort_order'    =>'required',

                'parent_id'     =>'required',

                //'category_image'=>'required|file|max:10000',

            ]);



        $category = Category::where('id', $cate_id)->first();



        $category->name        = $request->name;

        $category->description = $request->description;

         $category->status      = $request->status;

        $category->sort_order  = $request->sort_order;

        $category->parent_id   = $request->parent_id;

        $category->image       = $request->file('category_image')?$request->file('category_image')->store('category'):$category->image;



        $category->save();

       





       return redirect('admin-panel/categories')->with(['class'=>'success', 'message'=>'Category Successfully updated!']);

    }



    public function destroy($cate_id){

        Category::where('id', $cate_id)->delete();

    	return response()->json(['class'=>'success', 'message'=>'Category Successfully deleted!']);





    }

}

