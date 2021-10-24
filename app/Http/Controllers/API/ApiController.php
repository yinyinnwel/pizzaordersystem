<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    //list
    public function categoryList(){
        $category = Category::get();

        $response = [
            'status' => 200,
            'message' => "success",
            'data' => $category
        ];
        return Response::json($response);
    }

    //create
    public function createCategory(Request $request){
      //  $request->header();

        $data = [
            'category_name' => $request->categoryName,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        Category::create($data);

        $response = [
            'status' => 200,
            'message' => 'success',
        ];

        return Response::json($response);
    }

    //details
    public function categoryDetails(Request $request){
        $id = $request->id;

        $data = Category::where('category_id',$id)->first();

        if(!empty($data)){
            return Response::json([
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ]);
        }
        return Response::json([
            'status' => 200,
            'message' => 'fail',
            'data' => $data
        ]);
    }

    //delete
    public function categoryDelete($id){
        $data = Category::where('category_id',$id)->first();

        if(empty($data)){
            return Response::json([
                'status' => 200,
                'message' => 'There is no such data!',
                'data' => $data
            ]);
        }else{
            Category::where('category_id',$id)->delete();
            return Response::json([
                'status' => 200,
                'message' => 'success',
            ]);
        }
    }

    //update
    public function categoryUpdate(Request $request){
        $updateData = [
            'category_id' => $request->id,
            'category_name' => $request->categoryName,
            'updated_at' => Carbon::now(),
        ];

        $check = Category::where('category_id',$request->id)->first();

        if(!empty($check)){
            Category::where('category_id',$request->id)->update($updateData);
            return Response::json([
                'status' => 200,
                'message' => 'success',
            ]);
        }

        return Response::json([
            'status' => 200,
            'message' => 'There is no such data!',
        ]);
    }
}
