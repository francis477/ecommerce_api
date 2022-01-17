<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){


        $response =  [
            'status'=>'success',
            'message' => "Request Send Successful",
           ];
           return response($response,200);
    }


    public function home_product(Request $request){

        $query_product = Product::with(['category','brand','product_image']);


        if($request->search){
            $query_product->where('pro_name', 'LIKE', '%' .$request->search.'%');
        }


        if($request->category){
            $query_product->whereHas('category', function($query) use ($request){
                $query->where('slug', $request->category);
            });
        }

        if($request->brand){
            $query_product->whereHas('brand', function($query) use ($request){
                $query->where('slug', $request->brand);
            });
        }
        // if($request->brand){
        //     $query_product->whereHas('brand', function($query) use ($request){
        //         $query->where('slug', $request->brand);
        //     });
        // }

        if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
            $sortBy = $request->sortBy;
        }else{
            $sortBy = 'id';
        }

        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder = $request->sortOrder;
        }else{
            $sortOrder = 'desc';
        }

        if($request->perpage){
$perpage = $request->perpage;
        }else{
            $perpage = 10;
        }

        if($request->paginate){
            $product = $query_product->OrderBY($sortBy,$sortOrder)->paginate($perpage);
        }else{
            $product = $query_product->OrderBY($sortBy,$sortOrder)->get();
        }



        $success = 'Requested Successful';
        return response([

            'message' =>  $success,
            'data'=> [
                'items' => $product
            ]
        ]);
    }


    public function home_product_id($id){

        $query_product = Product::with(['category','brand','product_image'])->where('id',$id)->first();
        if(!$query_product){
            return notFound();
        }



        $success = 'Requested Successful';
        return response([

            'message' =>  $success,
            'data'=> [
                'items' => $query_product
            ]
        ]);
    }
}
