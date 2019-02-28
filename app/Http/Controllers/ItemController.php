<?php

namespace App\Http\Controllers;

use App\Item;
use App\Utils;
use App\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        $items      = Item::all();
        $categories = Category::all();

        return view('items.index', compact('items', 'categories'));
        //return response()->json(Item::all(), 200);
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
            'category_id' => 'required',
            'image' => 'required|image',
        ]);

        $item = new Item();
        $item->setConnection('mysql2');

        $item->name = $request->name;
        $item->price = $request->price;
        $item->description = $request->description;
        $item->category_id = $request->category_id;

        if($request->hasFile('image')){
            $fileName = Utils::saveImage($request, 'image', 'img/food-item');
            $item->image = $fileName;
        }else{
            return response()->json(["status" => 'no-image']);
        }

        if($item->save()){
            return response()->json([
                'status'  => (bool) $item,
                'data'    => $item,
                'id' => $item->id,
                'message' => 'Item Created Successfully!'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating item'
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
}
