<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;


use App\User;
use App\Role;


class UsersManagerController extends Controller
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
        $request->user()->authorizeRoles(['admin']);

        $users = [];
        $roles = Role::all();

        foreach ($roles as $role) {
            $roleName = $role->name;
            $users[$roleName] = User::whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })->get();
        }

        return view('admin/manager_user', compact('users','roles'));
    }

    public function add(Request $request){
        $request->user()->authorizeRoles(['admin']);

        request()->validate([
            'name' => 'required|min:2|max:50',
            'email' => 'required|email|unique:users',
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name should not be greater than 50 characters.',
        ]);

        $password = $this->generateRandomString(10);

        $user = User::create([
          'name'     => request()->name,
          'email'    => request()->email,
          'password' => bcrypt($password),
          'remember_password' => $password,
        ]);
        $user
           ->roles()
           ->attach(Role::where('name', request()->role_name)->first());
        
  //       $data = array(
  //           'subject' => 'Mail account registration',
  //           'from' => 'admin@agl-tool.com',
  //           'from_name' => 'Allgrow labo tool',
  //           'name' => request()->name,
  //           'email' => request()->email,
  //           'password' => $password
  //       );
  //       try {
  //       	Mail::to(request()->email)->send(new SendMail($data));
  //       	Storage::append('mail\log_sent_'.request()->email.'.log', "\nSent email : " . request()->email . "\n Password: " . $password);
  //       } catch (Exception $ex) {
		//     // Debug via $ex->getMessage();
		//     Storage::append('mail\log_sent_'.request()->email.'.log', "\nCan not sent email :" . request()->email);
		//     return back()->withErrors('Can not sent email');
		// }

        return back()->with('success', 'User created successfully.');
    }

    public function edit(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        $role  = Role::where('name', $request->input('role_name'))->first();

        $userId = (int)$request->input('id'); 
        
        $user = User::find($userId);
        $user->roles()->sync($role);

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    public function delete(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $userId = (int)$request->input('id'); 
        $user = User::find($userId);
        $user->roles()->sync([]);
        $user->delete();

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        $password = $this->generateRandomString(10);
        $userId = (int)$request->input('id'); 
        $user = User::find($userId);
        $user->password = bcrypt($password);
        $user->remember_password = $password;
        $user->save();

  //       $data = array(
  //           'subject' => 'Mail reset password',
  //           'from' => 'admin@agl-tool.com',
  //           'from_name' => 'Allgrow labo tool',
  //           'name' => $user->name,
  //           'email' => $user->email,
  //           'password' => $password,
  //       );

  //       try {
  //       	Mail::to($user->email)->send(new SendMail($data));
  //       	Storage::append('mail\log_resent_'.$user->email.'.log', "\nRe_sent email : " . $user->email . "\n Password: " . $password);
  //       } catch (Exception $ex) {
		//     // Debug via $ex->getMessage();
		//     Storage::append('mail\log_resent_'.$user->email.'.log', "\nCan not re_sent email :" . $user->email);
		//     return back()->withErrors('Can not sent email');
		// }

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}