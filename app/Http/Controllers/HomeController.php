<?php

namespace App\Http\Controllers;
use App\Http\Middleware;
use Illuminate\Http\Request;
use Redirect,Response,DB,Config;
use App\modelcrud;
use App\modelorder;
use App\modelpurchase_order;
use Datatables;

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
     * @return \Illuminate\View\View
     */
    
    public function index()
    {
        $data = DB::table('order')->where('customer_id','=',  auth()->user()->id)->join('purchase_order','order.id','=','purchase_order.order_id')->select('purchase_order.*','order.order_number')->get();
        $wee = DB::table('order')->where('customer_id','=',  auth()->user()->id)->get();
        $history = DB::table('order')->where('customer_id','=',  auth()->user()->id)->join('purchase_order','order.id','=','purchase_order.order_id')->select('purchase_order.*','order.order_number')->get();
          
    
        return view('dashboard', compact('data', 'wee','history'));
    }
    public function json(){
    $customers = DB::table('customer')->select('*')->where('id','=',  auth()->user()->id  );
    return datatables()->of($customers)->make(true);
   }
   public function json1(){
    $order = DB::table('order')->where('customer_id','=',  auth()->user()->id)->select('*');
    return datatables()->of($order)->make(true);
   }
   public function json2(){
    $purchase_order = DB::table('order')->where('customer_id','=',  auth()->user()->id)->join('purchase_order','order.id','=','purchase_order.order_id')->select('purchase_order.*','order.order_number')->get();
    return datatables()->of($purchase_order)->addColumn('action', function ($purchase_order) {
                return '<a href="#edit-'. $purchase_order->order_number.'" class="btn btn-primary btn-link btn-sm" target="new"><i class="material-icons">edit</i></a>';
            })
            ->editColumn('id', '{{$id}}')
            ->make(true);
   }
  
}
