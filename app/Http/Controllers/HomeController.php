<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\Events;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function save(Request $request){

        #dd($request->all());

      #  dd(strtotime($request->date_from) );
        $event = new Events;

        $event->user_id = Auth::id();
        $event->name = $request->event_name;
        $event->start = $request->date_from;
        $event->end =  $request->date_to;
        $event->days = json_encode($request->days);
        
        $event->save();

        return redirect()->route('home')->with('status', 'Event saved!');



    }

}
