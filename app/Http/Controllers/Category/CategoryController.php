<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class CategoryController extends Controller
{

    function __construct()
    {
        $this->middleware(['role:superadmin|admin']);
    }

    public function index(Request $request)
    {

        if (!auth()->user()->can('category.view')) {
            return abortAction();
        }



        if($request->size){
            $size = $request->size;
                    }else{
                        $size = 10;
                    }


        // $pagination = Category::paginate($size);

        $pagination = Category::all();

// $pagination->firstItem(); // Returns the number of the first item from the current page

// $pagination->lastItem(); // Returns the number of the last item from the current page

// $pagination->total(); // Returns the total amount of items

// $results->count()
// $results->currentPage()
// $results->firstItem()
// $results->hasMorePages()
// $results->lastItem()
// $results->lastPage() (Not available when using simplePaginate)
// $results->nextPageUrl()
// $results->perPage()
// $results->previousPageUrl()
// $results->total() (Not available when using simplePaginate)
// $results->url($page)

        $success = 'Requested Successful';
        return response([
            'status' => 200,
            'message' =>  $success,
            'data' =>
            [
                // "totalItems" => $pagination->total(),
                // "totalPage" => $pagination->count(),
                // "currentPage" => $pagination->currentPage(),

                'items' => $pagination
            ],

        ]);
    }


    public function store(Request $request)
    {
        if (!auth()->user()->can('category.create')) {
            return abortAction();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            failedValidation($validator);
        }

        $slug = Str::slug($request->name, '-');
        $category = new Category([
            'name' => $request->name,
            'slug' => $slug,
            'user_id' => auth()->user()->id
        ]);
        $category->save();
        $success = 'Created Successful';
        return response(['message' =>  $success, 'data' => $category]);
    }


    public function show($id)
    {
        if (!auth()->user()->can('category.view')) {
            return abortAction();
        }

        $category = Category::where("id", $id)->first();
        if(!$category){
            return notFound();
        }
        $data = [
            "id" => $category->id,
            "name" =>  $category->name,
            "slug" =>  $category->slug,
        ];
        $success = 'Requested Successful';
        return response(['message' => $success,'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('category.update')) {
            return abortAction();
        }

        $request->validate([
            'name' => 'required'
        ]);
        $slug = Str::slug($request->name, '-');
        $category = Category::where("id", $id)->first();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->user_id = auth()->user()->id;

        if ($category->save()) {
            $data = [
                "id" => $category->id,
                "name" =>  $category->name,
                "slug" =>  $category->slug,
                "created_at" => $category->created_at,
                "updated_at" => $category->updated_at
            ];
            $success = 'Updated Successful';
            return response(['user' => $data, 'message' =>  $success,]);
        } else {
            $success = 'Something Went Wrong';
            return response(['message' =>  $success,]);
        }
    }


    public function destroy($id)
    {
        if (!auth()->user()->can('category.delete')) {
            return abortAction();
        }
        $category = Category::where("id", $id)->first();
        if(!$category){
            return notFound();
        }

        Category::find($id)->delete();
        $success = 'Category deleted successfully';
        return response(['message' =>  $success]);
    }
}
