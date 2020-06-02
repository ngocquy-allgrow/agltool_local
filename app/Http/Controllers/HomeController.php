<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Tool;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'customer', 'staff', 'block']);
        $tools = Tool::all();
        return view('home', compact('tools',$tools));
    }

    public function changePassword(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'customer', 'staff', 'block']);
        return view('change_password');
    }

    public function changePasswordFunc(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'customer', 'staff', 'block']);

        request()->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Password is required',
            'password.min:8' => 'Name must be at least 8 characters.',
        ]);

        $user = Auth::user();    
        $user->password = bcrypt($request->input('password'));
        $user->remember_password = $request->input('password');
        $user->save();

        return redirect()->route('home');
    }
}
