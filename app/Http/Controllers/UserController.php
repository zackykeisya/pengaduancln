<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::simplePaginate(5);
        return view('users.index', compact('users'));
    }

    public function login(){
        return view('login-page');
    }

    public function loginAuth(Request $request){
        // dd($request->all());
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);



        $user = $request->only(['email','password']);

        if(auth()->attempt($user)){
            return redirect()->route('home');
        }else{
            return redirect()->back();
        }

        
    }

    public function register(){
        return view('register');
    }

    public function createUser(Request $request){
    
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
            $user=User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'GUEST',
            ]);

            Auth::login($user);
            return redirect()->route('home')->with('success', 'Akun berhasil dibuat.');
    }


    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('home.akun')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function reset($id){
        
        $user = User::find($id);

        $email = $user->email;
        $password = explode('@', $email)[0];
       
        $user=User::where('id', $id)->update([
            'password' => Hash::make($password),
        ]);
        return redirect()->route('home.akun')->with('success', 'Password reset successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id)
    // {
    //     $user = User::find($id);
    //     return view('users.edit', compact('user'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request,$id)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'role' => 'required',
    //     ]);

    //     User::where('id', $id)->update([
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role,
    //     ]);

    //     return redirect()->route('home.akun')->with('success', 'User updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return redirect()->route('home.akun')->with('success', 'User deleted successfully');
    }
}
