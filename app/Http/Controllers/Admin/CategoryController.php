<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{

    //direct addCategory page
    public function addCategory(){
        return view('admin.category.addCategory');
    }

    //create Category page
    public function createCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'category_name' => $request->name
        ];
        Category::create($data);
        return redirect()->route('admin#category')->with(['categorySuccess'=>'Category Added...']);
    }

    //direct category page
    public function category(){

        if(Session::has('CATEGORY_SEARCH')){
            Session::forget('CATEGORY_SEARCH');
        }

        $data = Category::select('categories.category_id','categories.category_name',DB::raw('count(pizzas.category_id)as count'))
                        ->leftJoin('pizzas','pizzas.category_id','categories.category_id')
                        ->groupBy('categories.category_id','categories.category_name')
                        ->paginate(7);
        //dd($data->toArray());
        return view('admin.category.list')->with(['category'=>$data]);
    }

    //delete category page
    public function deleteCategory($id){
        Category::where('category_id',$id)->delete();
        return back()->with(['deleteSuccess'=>'Category Deleted!']);
    }

    //edit category page
    public function editCategory($id){
        $data = Category::where('category_id',$id)->first();
        return view('admin.category.update')->with(['category'=>$data]);
    }

    //update category page
    public function updateCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'category_name' => $request->name
        ];
        Category::where('category_id',$request->id)->update($updateData);
        return redirect()->route('admin#category')->with(['updateSuccess'=>'Category Updated!']);
    }

    //search category page
    public function searchCategory(Request $request){
        $data = Category::select('categories.category_id','categories.category_name',DB::raw('count(pizzas.category_id)as count'))
                        ->leftJoin('pizzas','pizzas.category_id','categories.category_id')
                        ->where('categories.category_name','like','%'.$request->searchData.'%')
                        ->groupBy('categories.category_id','categories.category_name')
                        ->paginate(7);

        Session::put('CATEGORY_SEARCH',$request->searchData); //keep search key

        $data->appends($request->all());
        return view('admin.category.list')->with(['category'=>$data]);
    }

    //category download
    public function categoryDownload(){

        if(Session::has('CATEGORY_SEARCH')){
            $category = Category::select('categories.category_id','categories.category_name',DB::raw('count(pizzas.category_id)as count'))
                        ->leftJoin('pizzas','pizzas.category_id','categories.category_id')
                        ->where('categories.category_name','like','%'.Session::get('CATEGORY_SEARCH').'%')
                        ->groupBy('categories.category_id','categories.category_name')
                        ->get();
        }else{
            $category = Category::select('categories.category_id','categories.category_name',DB::raw('count(pizzas.category_id)as count'))
                        ->leftJoin('pizzas','pizzas.category_id','categories.category_id')
                        ->groupBy('categories.category_id','categories.category_name')
                        ->get();
        }

        $csvExporter = new \Laracsv\Export(); //object laracsv

        $csvExporter->build($category, [
            'category_id' => 'No',
            'category_name' => 'Name',
            'count' => 'Product Count',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();

        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'categoryList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

}
