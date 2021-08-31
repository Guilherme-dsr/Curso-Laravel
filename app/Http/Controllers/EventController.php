<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(){

        $search = request('search');

        if($search){
        $events= Event::where([
            ['title', 'like','%'.$search.'%']
        ])->get();

        } else{
        $events = Event::all();
        }

        return view('welcome',['events'=>$events, 'search'=>$search]);
    }

    public function create(){
        return view('events.create');
    }

    public function contato(){
        return view('contact');
    }

    public function store(Request $request){

        $event = new Event();

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        //image upload
        if($request->hasfile('image') && $request->file("image")->isvalid()) {
         
            $requestimage = $request->image;

            $extension = $requestimage->extension();
            
            $imageName = md5($requestimage->getClientOriginalName() . strtotime("now") . "." . $extension);

            $requestimage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        

        }

        $user = auth()->user();
        $event->userid = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id) {

        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
        
    }
    
}
