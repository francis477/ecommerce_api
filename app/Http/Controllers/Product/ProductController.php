<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    //
    public function store(Request $request)
    {
        if (
            !auth()
                ->user()
                ->can('product.create')
        ) {
            return abortAction();
        }
        $validator = Validator::make($request->all(), [
            'pro_name' => 'required',
            'pro_price' => 'required',
            'pro_details' => 'required',
            'pro_stock' => 'required',
            'cat_id' => 'required',
            'brand_id' => 'required',
            'pro_image' => 'required',
            'pro_image.*' => 'required',
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $product_tb = new Product();
        $product_img_tb = new ProductImage();
        $product_tb->pro_name = $request->pro_name;
        $product_tb->pro_price = $request->pro_price;
        $product_tb->pro_details = $request->pro_details;
        $product_tb->pro_stock = $request->pro_stock;
        $product_tb->cat_id = $request->cat_id;
        $product_tb->brand_id = $request->brand_id;
        $product_tb->user_id = auth()->user()->id;


        DB::transaction(function () use (
            $product_tb,
            $product_img_tb,
            $request
        ) {
            $main_arr = [];
            if ($product_tb->save()) {
                $multiple_img = $request->file('pro_image');
                if ($request->hasfile('pro_image')) {
                    foreach ($request->file('pro_image') as $multiple_img) {
                        $name_gen = hexdec(uniqid()) . '.' . $multiple_img->getClientOriginalExtension();
                        Image::make($multiple_img)->encode('webp', 90)->resize(200, 250)->save(public_path('image/product/' . $name_gen));
                        $last_img = $name_gen;

                        $data = array(
                            'name' => $last_img,
                            'pro_id' => $product_tb->id,
                        );
                        $main_arr[] = $data;
                    }
                }
            }

            if ($product_img_tb->insert($main_arr)) {
            }
        });

        $success = 'Created Successful';
        return response(['message' => $success]);
    }

    public function updateImage(Request $request, $id)
    {
        if (!auth()->user()->can('product.update')) {
            return abortAction();
        }

        $image = ProductImage::find($id);
        if (!$image) {
            return response(['message' => 'Id not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'pro_image' => 'required|mimes:jpg,png,jpeg,webp|max:1048'

        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }


        if ($request->hasFile('pro_image')) {
            $destination = public_path('image/product/' . $image->name);
            if (File::exists($destination)) {
                unlink($destination);
            }
            $pro_img = $request->file('pro_image');

            $name_gen =hexdec(uniqid()).'.'.$pro_img->getClientOriginalExtension();
            Image::make($pro_img)->encode('webp', 90) ->resize(200, 250)->save(public_path('image/product/' . $name_gen));
             $last_img = $name_gen ;
             $data = [
                 'name' => $last_img,
             ];
             $image->update($data);
             $success = 'Updated Successful';
             return response(['message' => $success]);

        }else{
            $success = 'Something Went Wrong';
            return response(['message' => $success]);
        }




    }




    public function update(Request $request, $id)
    {
        if (
            !auth()
                ->user()
                ->can('product.update')
        ) {
            return abortAction();
        }

        $product_tb = Product::where('id', $id)->first();
        if (!$product_tb) {
            return response(['message' => 'Id not found'], 404);
        }


        $validator = Validator::make($request->all(), [
            'pro_name' => 'required',
            'pro_price' => 'required',
            'pro_details' => 'required',
            'pro_stock' => 'required',
            'cat_id' => 'required',
            'brand_id' => 'required'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        } else {
            // $product_tb->update($validator->validated());
            $product_tb->pro_name = $request->pro_name;
            $product_tb->pro_price = $request->pro_price;
            $product_tb->pro_details = $request->pro_details;
            $product_tb->pro_stock = $request->pro_stock;
            $product_tb->cat_id = $request->cat_id;
            $product_tb->brand_id = $request->brand_id;
            $product_tb->user_id = auth()->user()->id;

            DB::transaction(function () use (
                $product_tb,
                $request
            ) {
                $product_tb->save();
            });

            $success = 'Update Successful';
            return response(['message' => $success]);
        }
    }


    public function destroy($id)
    {

        try {
            if (!auth()->user()->can('product.delete')) {
                return abortAction();
            }
            $product = Product::where("id", $id)->first();

            if (!$product) {
                return notFound();
            }
            $pro_img = ProductImage::where("pro_id", $product->id)
                ->get();
            $get_count = ProductImage::where("pro_id", $product->id)->count();
            if ($get_count > 0) {
                foreach ($pro_img as $multiple_img) {
                    $destination = public_path('image/product/' . $multiple_img['name']);
                    if (File::exists($destination)) {
                        unlink($destination);
                    }
                }
            }
            Product::find($id)->delete();
            $success = 'Deleted successfully';
            return response(['message' =>   $success]);
        } catch (\Throwable $e) {
            //throw $th;
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []]);
        }
    }
}
