<?php

namespace App\Http\Controllers\Social\Questions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Auth;

class QuestionsController extends BaseController
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
        $request->user()->authorizeRoles(['admin', 'customer', 'staff']);

        $user_id = Auth::user()->id;
        

        return $user_id;       
    }
}
