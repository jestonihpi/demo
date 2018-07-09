<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;

use App\Events;

use Auth;

use DateTime;

use DateInterval;

use DatePeriod;

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
        
        return view('home' ,['events' => Events::where('user_id',Auth::id() )->orderBy('id','desc')->get() ]  );
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

    public function view(Request $request){

        $event = Events::find($request->id);
        $start    = new DateTime($event->start);
        $end      = (new DateTime($event->end))->modify('+1 day');
        $interval = new DateInterval('P1D');
        $period   = new DatePeriod($start, $interval, $end);
        
        
        return view('view' ,['period' => $period ,'details' => $event ,'selected_days' => json_decode($event->days) ]  );

    }

}
