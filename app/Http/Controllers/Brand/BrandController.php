<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware(['role:superadmin|admin']);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('brand.view')) {
            return abortAction();
        }

        $category = Brand::all();

        $success = 'Requested Successful';
        return response(['user' => $category, 'message' =>  $success,]);
    }


    public function store(Request $request)
    {
        if (!auth()->user()->can('brand.create')) {
            return abortAction();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $slug = Str::slug($request->name, '-');
        $brand = new Brand([
            'name' => $request->name,
            'slug' => $slug,
            'user_id' => auth()->user()->id,
        ]);
        $brand->save();
        $success = 'Created Successful';
        return response(['message' =>  $success, 'data' => $brand]);
    }


    public function show($id)
    {
        if (!auth()->user()->can('brand.view')) {
            return abortAction();
        }

        $brand = Brand::where("id", $id)->first();
        if(!$brand){
            return notFound();
        }else{
            $data = [
                "id" => $brand->id,
                "name" =>  $brand->name,
                "slug" =>  $brand->slug,
            ];
            $success = 'Requested Successful';
            return response(['message' => $success,'data' => $data]);
        }

    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('brand.update')) {
            return abortAction();
        }
        $brand = Brand::where("id", $id)->first();
        if(!$brand){
            return notFound();
        }else{
            $request->validate([
                'name' => 'required'
            ]);
            $slug = Str::slug($request->name, '-');

            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->user_id = auth()->user()->id;

            if ($brand->save()) {
                $data = [
                    "id" => $brand->id,
                    "name" =>  $brand->name,
                    "slug" =>  $brand->slug,
                    "created_at" => $brand->created_at,
                    "updated_at" => $brand->updated_at
                ];
                $success = 'Updated Successful';
                return response(['user' => $data, 'message' =>  $success,]);
            } else {
                $success = 'Something Went Wrong';
                return response(['message' =>  $success,]);
            }
        }

    }


    public function destroy($id)
    {
        if (!auth()->user()->can('brand.delete')) {
            return abortAction();
        }
        $brand = Brand::where("id", $id)->first();
        if(!$brand){
            return notFound();
        }else{

            Brand::find($id)->delete();
            $success = 'Brand deleted successfully';
            return response(['message' =>  $success]);
        }

    }
}
