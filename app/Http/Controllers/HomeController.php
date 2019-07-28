<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Polygon;
use SebastianBergmann\Environment\Console;

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
        $polygons = DB::table('polygons')->get();
        return view('home', [
            'polygons' => $polygons
        ]);
    }

    public function show(Request $request){
        $string = $request->get('data');

        $polygon = new Polygon([
            'name' => json_decode($string).name,
            'polyString' => json_decode($string).poly
        ]);
        $polygon->save();
    }
}
