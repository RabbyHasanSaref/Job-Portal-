<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Supports\Helper;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    use Helper;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$this->can(request()->route()->action['as'])){
                return $this->returnData(5000, null, 'You are not authorized to access this page');
            }
            return $next($request);
        });
        $this->model = new Company();
    }

    public function index()
    {
            $data = $this->model->orderBy('id','DESC')->paginate(12);
            return $this->returnData(2000, $data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = $this->model->Validator($request->all());

        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors(), 'status' => 3000], 100);
        }
        $this->model->fill($request->all());
        $this->model->save();
        return $this->returnData(2000, $this->model);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
//        if (!$this->can('category_edit')){
//            return $this->returnData(5000, null, 'You do not have permission to edit this category');
//        }

        try {
            $validator = $this->model->Validator($request->all());

            if ($validator->fails()) {
                return response()->json(['result' => $validator->errors(), 'status' => 3000], 200);
            }
            $category = $this->model->where('id', $request->input('id'))->first();
            if ($category) {
                $category->fill($request->all());
                $category->update();

                return $this->returnData(2000, $category);
            }
            return $this->returnData(3000, null, 'Category not found');

        } catch (\Exception $e) {
            return response()->json(['result' => null, 'message' => $e->getMessage(), 'status' => 5000]);
        }
    }
    public function destroy($id)
    {
        try {
            $data = $this->model->where('id',$id)->first();
            if ($data){
                $data->delete();

                return $this->returnData(2000, null, 'Category deleted successfully');
            }
            return $this->returnData(3000, null, 'Category not found');

        }catch (\Exception $exception){
            return $this->returnData(5000, $exception->getMessage(), 'Something Wrong');
        }
    }
}
