<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Tool;
use Route;


class ToolsManagerController extends Controller
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
        $tools = Tool::all();

        return view('admin/manager_tool', compact('tools',$tools));
    }

    public function add(Request $request){
        $request->user()->authorizeRoles(['admin']);      

        if(!Route::has(request()->route))
        {
            return back()->withErrors(['route' => 'Route is not exist']);
        }


        request()->validate([
            'name' => 'required|min:2|max:50',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'route' => 'required',
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name should not be greater than 50 characters.',
        ]);
        
        $tool = Tool::create([
          'name'     => request()->name,
          'image'     => 'default.png',
          'route'    => request()->route,
          'status' => 0,
        ]);

        if(request()->image){
            $imageName = $tool->id.'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('img/tools'), $imageName);
            $tool->image = $imageName;
            $tool->save();
        }

       return back();
        
    }

    public function edit(Request $request){
        $request->user()->authorizeRoles(['admin']);      

        $tool = Tool::find($request->input('id'));
        $tool->name = $request->input('name');
        $tool->route = $request->input('route');
        $tool->status = $request->input('status');
        $tool->save();

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    public function uploadImage(Request $request){
        $request->user()->authorizeRoles(['admin']);

        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = $request->input('id').'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('img/tools'), $imageName);

        $tool = Tool::find($request->input('id'));
        $tool->image = $imageName;
        $tool->save();
        
        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

    public function delete(Request $request){
        $request->user()->authorizeRoles(['admin']);      

        $tool = Tool::find($request->input('id'));
        $tool->delete();

        return response()->json([
            'code' => '200',
            'state' => 'ok'
        ]);
    }

}