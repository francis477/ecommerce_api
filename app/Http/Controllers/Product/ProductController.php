<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\File;
use Validator;
class ProductController extends Controller
{
    function __construct()
    {
      $this->middleware(['role:superadmin|admin']);
    }

    public function index(Request $request){
        if (!auth()->user()->can('product.view')) {
            return abortAction();
        }

        $validator = Validator::make($request->all(), [
            'perpage' => 'numeric|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $query_product = Product::with(['category','brand','product_image']);





        if($request->minPrice){
            $minPrice = $request->minPrice;
        }else{
            $minPrice = '';
        }
        if($request->maxPrice){
            $maxPrice = $request->maxPrice;
        }else{
            $maxPrice = '';
        }

        if($request->keyword){
            $query_product->where('pro_name', 'LIKE', '%' .$request->keyword.'%');
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




        if($request->brand){
            $query_product->whereHas('brand', function($query) use ($request){
                $query->where('slug', $request->brand);
            });
        }

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
            $product = $query_product->whereBetween('pro_price',[$minPrice,$maxPrice])->OrderBY($sortBy,$sortOrder)->paginate($perpage);
        }else{
            $product = $query_product->OrderBY($sortBy,$sortOrder)->get();
        }



        $success = 'Created Successful';
        return response(['product' => $product, 'message' =>  $success,]);
    }




    public function get_product(Request $request){
        if (!auth()->user()->can('product.view')) {
            return abortAction();
        }

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

        // foreach($product as $value){

        //     $p_product [] = [
        //         "id" => $value['id'],
        //         "pro_name" => $value['pro_name'],
        //         "old_price"=> $value['old_price'],
        //         "pro_price" => $value['pro_price'],
        //         "pro_details" =>  $value['pro_details'],
        //         "pro_stock" => $value['pro_stock'],
        //         "rating"=> $value['rating'],
        //         "category_id"=> $value['category_id'],
        //         "brand_id"=> $value['brand_id'],
        //         "created_at"=> $value['created_at'],
        //     ];


        //     foreach ($product->category as $value) {

        //         $p_category [] = [
        //             "name" => $value['name'],

        //         ];

        //     };


        //     foreach ($product->brand as $value) {

        //         $p_brand [] = [
        //             "name" => $value['name'],

        //         ];

        //     };


        // }

        $success = 'Requested Successful';
        return response([

            'message' =>  $success,
            'data'=> [
                'items' => $product
            ]
        ]);
    }


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
            'old_price' => 'required',
            'pro_details' => 'required',
            'pro_stock' => 'required',
            'category_id' => 'required',
            'rating' => 'required',
            'brand_id' => 'required',
            'pro_image' => 'required|max:1000',
            'pro_image.*' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }else{
        // $cal_rate = ($request->rating * 5) / 100;
        // number_format($cal_rate,0);
        $product_tb = new Product();
        $product_img_tb = new ProductImage();
        $product_tb->pro_name = $request->pro_name;
        $product_tb->pro_price = $request->pro_price;
        $product_tb->old_price = $request->old_price;
        $product_tb->pro_details = $request->pro_details;
        $product_tb->pro_stock = $request->pro_stock;
        $product_tb->category_id = $request->category_id;
        $product_tb->rating =$request->rating;
        $product_tb->brand_id = $request->brand_id;
        $product_tb->user_id = auth()->user()->id;


        DB::transaction(function () use (
            $product_tb,
            $product_img_tb,
            $request
        ) {
            $main_arr = [];
           $product_tb->save();
                $multiple_img = $request->file('pro_image');
                if ($request->hasfile('pro_image')) {
                    foreach ($request->file('pro_image') as $multiple_img) {
                        $name_gen = hexdec(uniqid());
                        Image::make($multiple_img)->encode('webp', 90)->resize(200, 250)->save(public_path('image/product/' . $name_gen . '.webp'));
                        $last_img = $name_gen . '.webp';

                        $data = array(
                            'name' => $last_img,
                            'pro_id' => $product_tb->id,
                        );
                        $main_arr[] = $data;
                    }
                }

            $product_img_tb->insert($main_arr);


        });
    }
        $success = 'Created Successful';
        return response(['message' => $success]);

    }

    public function updateImage(Request $request, $id)
    {
        if (!auth()->user()->can('product.update')) {
            return abortAction();
        };

        $image = ProductImage::find($id);
        if (!$image) {
            return response(['message' => 'Id not found'], 404);
        };

        $validator = Validator::make($request->all(), [
            'pro_image' => 'required|image|mimes:jpg,png,jpeg,webp|max:1028'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        };
        if ($request->hasFile('pro_image')) {
            $pro_img = $request->file('pro_image');
            $name_gen = hexdec(uniqid());
            $destination = public_path('image/product/' . $image->name);
            if (File::exists($destination)) {
                unlink($destination);
            }
            Image::make($pro_img)->encode('webp', 90)->resize(200, 250)->save(public_path('image/product/' . $name_gen . '.webp'));
            $last_img = $name_gen . '.webp';

            $data = [
                'name' => $last_img
            ];
            $image->update($data);
            $success = 'Updated Successful';
            return response(['message' => $success]);
        } else {
            $success = 'Something Went Wrong';
            return response(['message' => $success]);
        }
    }




    public function update(Request $request, $id)
    {
        if (
            !auth()->user()->can('product.update')
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
            'old_price' => 'required',
            'pro_details' => 'required',
            'pro_stock' => 'required',
            'rating' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        } else {

            // $cal_rate = ($request->rating * 5) / 100;
            $product_tb->pro_name = $request->pro_name;
            $product_tb->pro_price = $request->pro_price;
            $product_tb->old_price = $request->old_price;
            $product_tb->pro_details = $request->pro_details;
            $product_tb->pro_stock = $request->pro_stock;
            $product_tb->category_id = $request->category_id;
            $product_tb->rating = $request->rating;
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
            $product = Product::find($id);

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
            return response()->json(['message' =>   $success]);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []]);
        }
    }

    public function destroy_image($id)
    {

        try {
            if (!auth()->user()->can('product.delete')) {
                return abortAction();
            }
            $product = ProductImage::where("id", $id)->first();
            if (!$product) {
                return notFound();
            }
                $destination = public_path('image/product/' . $product->name);
                if (File::exists($destination)) {
                    unlink($destination);
                }
            ProductImage::find($product->id)->delete();
            $success = 'Deleted successfully';
            return response()->json(['message' =>   $success]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage()]);
        }
    }


    public function add_more_image(Request $request){

        if (!auth()->user()->can('product.update')) {
            return abortAction();
        }

        $validator = Validator::make($request->all(), [
            'pro_id' => 'required',
            'pro_image' => 'required|max:1000',
            'pro_image.*' => 'required|max:1000',
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $product_tb = new Product();
        $product_img_tb = new ProductImage();
        $id= $request->pro_id;
        $product_img_tb = new ProductImage();
        $product_tb = Product::find($id);
        if (!$product_tb) {
            return notFound();
        }else{

            $main_arr = [];
            if ($product_tb->save()) {
                $multiple_img = $request->file('pro_image');
                if ($request->hasfile('pro_image')) {
                    foreach ($request->file('pro_image') as $multiple_img) {
                        $name_gen = hexdec(uniqid());
                        Image::make($multiple_img)->encode('webp', 90)->resize(200, 250)->save(public_path('image/product/' . $name_gen . '.webp'));
                        $last_img = $name_gen . '.webp';

                        $data = array(
                            'name' => $last_img,
                            'pro_id' => $product_tb->id,
                        );
                        $main_arr[] = $data;
                    }
                }
            }

            if ($product_img_tb->insert($main_arr)) {
                $success = 'Added Successful';
                return response(['message' => $success]);
            }else{
                $success = 'Something Went Wrong';
                return response(['message' => $success]);
            }

        }



    }


    public function images($pro_id)
    {

        if (!auth()->user()->can('product.view')) {
            return abortAction();
        }

        // $product_img_tb = new ProductImage($id);

        $images = ProductImage::where('pro_id', $pro_id)->get();

        $success = 'Requested Successful';
        return response([
            'status' => 200,
            'message' =>  $success,
            'data' =>
            [
                'items' => $images
            ],

        ]);
    }

}






// Product Rating
// 100% ---------- 5
 //94% ---------- x = (94 * 5) / 100 (=) x = 4.7
