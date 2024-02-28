<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//user related routes
//name() is laravel way to redirect the user to login by assigning the name 
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
//this route register is the first one that will be accessed then submit is 
//clicked and sends it to the usercontroller and validates the data sent hten stpres it in the database 
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class,'logout'])->middleware('auth');



//blogposts related routes
//middleware('auth') authenticates that the user trying to access the domain has an account
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('auth');
//sends the data to the database
Route::post('/submit-post', [PostController::class,'storeNewPost'])->middleware('auth');
//shows the url of the post just created
Route::get('/post/{pushpost}', [PostController::class, 'showSinglePost']);
//routes to the delete function to the postcontroller
route::delete('post/{pushpost}',[PostController::class,'delete'])->middleware('can:delete,pushpost');
//view the edit form
route::get('/post/{pushpost}/edit', [PostController::class,'showEditForm'])->middleware('can:update,pushpost');
//submit the form
route::put('/post/{pushpost}', [PostController::class,'updatedPost'])->middleware('can:update,pushpost');

//profile related routes
//looks it up based on the username
route::get('/profile/{userprofile:username}', [UserController::class,'profile']);



