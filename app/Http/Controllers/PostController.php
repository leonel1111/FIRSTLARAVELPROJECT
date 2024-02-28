<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    //shows the edit form
    public function showEditForm(Post $pushpost) {
        return view("edit-post", [
            "post"=> $pushpost
        ]);
    }

    //editing the blog post content
    public function updatedPost(Post $pushpost,Request $request) {
        $updatethepost = $request->validate([
            "title"=> "required",
            "body"=> "required",
        ]);
        //strip any malicious tag that a user might add
        $updatethepost["title"] = strip_tags($updatethepost["title"]);
        $updatethepost["body"] = strip_tags($updatethepost["body"]);

        $pushpost->update($updatethepost);

        return back()->with("success","Post Updated");
    }

    //delete the post
    public function delete(Post $pushpost) {
       //allows the user to delete post
        $pushpost->delete();

        return redirect('/profile/' . auth()->user()->username)->with('success','Post successfully deleted');
    }
    public function showCreateForm() {
        //this code does not let the logged out user to access the create-post url
        // this function is much more convenient to put in the middleware
        // if(!auth()->check()){
        //     return redirect('/');
        // }
        return view("create-post");
    }

    public function storeNewPost(Request $request) {
        $incomingpost = $request->validate([
            "title"=> "required",
            "body"=> "required",
        ]);
        //strip any malicious tag that a user might add
        $incomingpost["title"] = strip_tags($incomingpost["title"]);
        $incomingpost["body"] = strip_tags($incomingpost["body"]);

        //show who the author of the post or the user_id of the post
        $incomingpost['user_id'] = auth()->id();
        //now we create a model where it will help us perform database actions
        //stores it in the database
        $newpost =  Post::create($incomingpost);
            //the ->with() call will flash a message once the user succcessfully fill the form out 
        return redirect("/post/{$newpost->id}")->with("success","Newpost succesfully Created");
    }

    //$pushpost can be routed to the web.php which handles the url for the post  
    //Post method before the $pushpost is hinting laravel what a blogpost should contain 
    public function showSinglePost(Post $pushpost) {
        //integrates a markdown function where markdown interprets #H1 ##h2 ###3 and so on strip tags only allows the elements in the 2nd argument
        $pushpost['body'] = strip_tags(Str::markdown($pushpost->body), '<p><ul><ol><li><strong><em><h3><br>');
        return view('single-post', ['post'=> $pushpost]);
    }
}
