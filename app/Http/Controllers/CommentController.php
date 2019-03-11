<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Item;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Config('database.connections.mysql2.database', session('db_name'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();

        return view('comments', compact('comments'));
    }

    public function all_comment()
    {
        $comments = Comment::latest()->get();

        return response()->json([
            'data' => $comments,
            'error' => false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $comment = new Comment();

        return view('comment-create', compact('comment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = true;

        $request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
            'comment' => 'required',
            'ratings' => 'required'
        ]);
        $item = Item::where('id', $request->id)->first();

        $comment = new Comment();

        $comment->user_id = Auth::user()->id;
        $comment->item_id = $item;
        $comment->comment = $request->comment;
        $comment->ratings = $request->ratings;
        
        if($comment->save()){
            return response()->json([
                'data' => $comment,
                'mesage' => 'Comment saved successfuly',
                'error' => !$result
            ]);
        }else{
            return response()->json([
                'error' => $result,
                'message' => 'Error saving comment, Try Again!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
