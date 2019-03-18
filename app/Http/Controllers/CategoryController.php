<?php

namespace App\Http\Controllers;

use Auth;
use App\Utils;

use App\Item;
use App\Category;
use Config;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
         $this->middleware('auth');
         if(session('db_name')!=null){
            Config::set('database.connections.mysql2.database', session('db_name')); 
         }
         
    }
    public function index ()
      {
         $categories = Category::all();

         if(strtolower(Auth::user()->role) == 'admin' || strtolower(Auth::user()->role) == 'manager'){
          return view('categories')->with('categories', $categories);
         }else{
           return abort(403);
         }
      }

      public function allCategories ()
      {
         $categories = Category::all();

         return $categories->toJson();

      }

      public function category_item ()
      {
         $category_item = Category::with(['items'])->get();

         return response()->json([
             'data' => $category_item,
            ], 200);
      }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category();

        return view('categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if(Category::where('name', $request->name)->get()->count() > 0){
            return response()->json([
                'error' => true,
                'message' => 'Category name already exists!'
            ]);
        }

        $category = new Category();

        $category->name = $request->name;

        if ($request->hasFile('file')){
            $fileName        = Utils::saveImageFromDz($request, 'file', 'img/categories');
            $category->image = $fileName;
         }else{
            return response()->json(["error" => true,"message" => 'no-image']);
         }

         if($category->save()){
             return response()->json([
                 "error" => false, 
                 'data' => $category,
                 'message' => 'Category created successfully'
             ]);
         }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating category'
            ]);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json($category, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $category = Category::where('id', $request->id)->first();

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category->name = $request->name;

         if ($request->hasFile('file'))
         {
            $fileName        = Utils::saveImageFromDz($request, 'file', 'img/categories');
            $category->image = $fileName;
         }

         $status = $category->update();

         return response()->json([
            'data' => $category,
            'error'  => !$status,
            'message' => $status ? 'Category updated!' : 'Error updating category'
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $status = Item::where('category_id', $category->id)->get()->count() < 1;

        if($status){
            $status = $category->delete();
        }

         return response()->json([
            'error'  => !$status,
            'message' => $status ? 'Category deleted' : 'The selected category already has items under it.'
         ]);
    }

    public function getCategoryItems(){
        $categoryItems = Category::with('items')->get();
        $formatted_array = array();

        foreach($categoryItems as $category){
            $temp = array(
                "category" => $category->name,
                "items" => sizeof($category->items)
            );

            array_push($formatted_array, $temp);
        }

        return response()->json([
            'message' => 'Data retrieved',
            'categories' => $formatted_array 
        ]);
    }
}
