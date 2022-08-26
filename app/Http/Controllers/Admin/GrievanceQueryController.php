<?php



namespace App\Http\Controllers\Admin;



use App\Http\Requests\Admin\Request;

use App\Http\Controllers\Controller;

use App\Model\GrievanceAndQuery;



class GrievanceQueryController extends Controller

{

    public function index(Request $request)

    {



        if($request->ajax()){



            $datas = GrievanceAndQuery::orderBy('created_at','asc')->select('id', 'type', 'refer_code','name', 'number', 'email', 'status', 'created_at');



            if($request->dateFrom && $request->dateTo){

                $datas->whereBetween('created_at', [$request->dateFrom.' 00:00:00', $request->dateTo.' 23:59:59']);

            }



            $totaldata = $datas->count();



            $search = $request->search['value'];



            if ($search) {



                $datas->where('id', 'like', '%'.$search.'%')



                ->orWhere('name', 'like', '%'.$search.'%')



                ->orWhere('email', 'like', '%'.$search.'%')



                ->orWhere('mobile', 'like', '%'.$search.'%')



                ->orWhere('refer_code', 'like', '%'.$search.'%');



            }



            # set datatable parameter 



            $result["length"]= $request->length;



            $result["recordsTotal"]= $totaldata;



            $result["recordsFiltered"]= $datas->count();



            $records = $datas->limit($request->length)->offset($request->start)->get();



            # fetch table records 



            $result['data'] = [];           



            foreach ($records as $data) {



             $result['data'][] =['sn'=>++$request->start,'user_detail'=>$data->name, 'mobile'=>$data->number, 'id'=>$data->id, 'email'=>$data->email, 'type'=>$data->type == 1 ? 'Grievance':'Query', 'date'=>$data->created_at->toDateString(), 'edit'=>'Edit', 'status'=>$data->status == 0 ?'Pending':'Closed'];

            }



            return $result;



        }

        return view('admin.grievance-query.list');

    }



    public function edit($id){



        $data = GrievanceAndQuery::where('id', $id)->first();



        return view('admin.grievance-query.edit', compact('data'));

    }





    public function store(Request $request){



        $this->validate($request, [

            'status'=>'required'

        ]);



        if($request->status == 1){

            $this->validate($request, [

                'remark'=>'required|max:200'

            ]);    



            $data = GrievanceAndQuery::select('id', 'status', 'remark')->where('id', $request->id)->first();



            $data->status = $request->status;

            $data->remark = $request->remark;

            $data->save();

        }



        return back()->with(['class'=>'success', 'message'=>'Status chnaged successfully']);

    }

}

