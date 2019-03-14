<?php

namespace App\Http\Controllers;

use App\Item;
use App\Utils;
use App\Category;
use App\Ingredient;

use Config;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth');
         Config::set('database.connections.mysql2.database', session('db_name'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        $items      = Item::with('category')->get();
        $categories = Category::all();

        return view('items', compact('items', 'categories'));
    }

    public function all_items ()
    {
        $items = Item::with(['category'])->get();

        return $items->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Item();

        return view('items.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'description' => 'required',
            'category_id' => 'required'
        ]);
        

        $item = new Item();

        $item->name = $request->name;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->category_id = $request->category_id;

        if($request->hasFile('file')){
            $fileName = Utils::saveImageFromDz($request, 'file', 'img/foods');
            $item->image = $fileName;
        }else{
            return response()->json(["message" => 'no-image', 'error'=>true]);
        }

        if($item->save()){
           if($request->ingredients != null){
            $ingredients = explode(",", $request->ingredients);
            $item_ingredients = array();

            if($ingredients != null){
               foreach($ingredients as $ingredient){
                  array_push($item_ingredients, array(
                     'item_id' => $item->id,
                     'name' => $ingredient
                  ));
               }

               Ingredient::insert($item_ingredients);
               
               return response()->json([
                  'error'  => false,
                  'data'    => $item,
                  'id' => $item->id,
                  'message' => 'Item created!'
               ]);

            }else{
               return response()->json([
                  'error'  => true,
                  'data'    => $item,
                  'id' => $item->id,
                  'message' => 'Item created. Could not save ingredients.'
               ]);
            }
           }else{
               return response()->json([
                  'error'  => false,
                  'data'    => $item,
                  'id' => $item->id,
                  'message' => 'Item created!'
               ]);
            }
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not create the item'
            ]);
        }
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item $item
     *
     * @return \Illuminate\Http\Response
     */
    public function show (Item $item)
    {
        return response()->json($item, 200);
    }

    /**
     * Edit a menu item
     */
    public function edit (Request $request)
    {
        //get the item
        $item = Item::where('id', $request->id)->first();

        //show the edit form and pass the item
        return view('items.edit', compact('item'));
    }

      /**
       * Update the specified resource in storage.
       *
       * @param  \Illuminate\Http\Request $request
       * @param  \App\Item                $item
       *
       * @return \Illuminate\Http\Response
       */
      public function update (Request $request, Item $item)
      {
         $status = $item->update(
            $request->only(['name', 'description', 'price', 'category_id'])
         );

         if($request->hasFile('image'))
         {
            $fileName = Utils::saveImage($request, 'image', 'images/foods');
            $status = $item->update(['image' => $fileName]);
         }

         return response()->json([
            'data' => $item,
            'status'  => $status,
            'message' => $status ? 'Item Updated!' : 'Error Updating Item'
         ]);
      }

      /**
       * Check the status of an Item
       */
      public function is_active (Request $request)
      {
         $item = Item::where('id', $request->id)->first();

         $isactive     = $request->active;
         $item->active = $isactive;

         if ($item->save())
         {
            return response()->json([
               'data'    => $item,
               'message' => 'Item is updated'
            ]);
         }
         else
         {
            return response()->json([
               'message' => 'Nothing to update',
               'error'   => true
            ]);
         }
      }


      public function toggleActive (Request $request, Item $item)
      {
         $item->active = !$item->active;

         if ($item->save())
         {
            return response()->json([
               'error'  => false,
               'data'    => $item,
               'message' => 'Item has been updated!'
            ]);
         }
         else
         {
            return response()->json([
               'message' => 'Could not update the item',
               'error'   => true
            ]);
         }
      }


      /**
       * Remove the specified resource from storage.
       *
       * @param  \App\Item $item
       *
       * @return \Illuminate\Http\Response
       */
      public function destroy (Item $item)
      {
         $status = $item->delete();

         return response()->json([
            'status'  => $status,
            'message' => $status ? 'Item Deleted!' : 'Error Deleting Item.'
         ]);
      }

      public function viewItem($item){
          $item = Item::with(['category', 'comments', 'orders', 'ingredients', 'images', 'comments.customer'])->where('id', $item)->first();
          $categories = Category::all();
          return view('item-details', compact('item', 'categories'));
      }
}
