<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    //profile related handling
    public function profile(User $userprofile) {
        return view("profile-post", [
            'username' => $userprofile->username,
            //userpost variable is defined and is found on the models user
            'userprofileposts' => $userprofile->userpost()->latest()->get(),
            'userpostcount' => $userprofile->userpost()->count()


        ]);
    }


    //logs the user out of the application and redirects the user to the homepage
    public function logout() {
        auth()->logout();
        //redirect to the homepage with a message saying YOU ARE LOGGED OUT accessed in the header.blade.php
        return redirect('/')->with('success','YOU ARE LOGGED OUT');
    }
    // modify the user homepage if he is logged in or a guest
    public function showCorrectHomepage() {
        // if the user is logged in with the help of authfunction and  check() looks into the data if it is true
        if (auth()->check()) {
            return view('homepage-feed');
        } else {
            return view('homepage');
        }
    }
    public function login(Request $request) {
        //validates that the data is not null
        $SignInData = $request->validate([
        // the names of the first variables are the names of the fields are stored in the view file(can be found on the input field) 
        'loginusername'=> 'required',
        'loginpassword'=> 'required'
        ]);
        //logs in the user
        //validates if the user uses the correct password and email using the auth() function
        if(auth()->attempt(['username' => $SignInData['loginusername'], 'password' => $SignInData['loginpassword']])) {
            // hass all the information about the incoming request $request
            // if the user gives us the right un and password, give them a session value tell their browser to store it in a cookie
            $request-> session()->regenerate();
            //this will redirect to the correct homepage as we already set the redirect if the user is loggedin in showCorrectHomepage
            //the ->with() call will flash a message once the user succcessfully fill the form out 
            return redirect('/')->with('success','YOU HAVE SUCCESSFULLY LOGGED IN');
        } else {
            return redirect('/')->with('error','Invalid LogIn');
        }
    }
    public function register(Request $request) {
    //registerData will be the name for storing all the data that is inputed by the USER
        $registerData = $request->validate([
            //validates the user input from the form and if he/she does not meet the required validation it sends it back to the homapge
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email'=> ['required', 'email', Rule::unique('users','email')],
            'password'=> ['required', 'min:8', 'confirmed'],
        ]);
        //hashes the password then stores it to the database 
        $registerData['password'] = bcrypt($registerData['password']);
        //store the values to the database
        //User is a model
        //migration =  add&remove columns to the database
        //model has to do something with the database/ perform CRUD operations that lives on the tables
        //save the registerdata to the usertemp temporarily to let the user log in automatically
        $usertemp = User::create($registerData);
        //log in the user after successfully creating an account
        auth()->login($usertemp);
        return redirect('/')->with('success','Account Succesfully Created');
    }
}
