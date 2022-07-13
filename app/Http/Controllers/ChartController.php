<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use App\Models\Expense;
use App\Models\StockHistory;
use App\Models\DistributionPayment;

class ChartController extends Controller
{
    public function index(){
        $stock_added = StockHistory::select(DB::raw('SUM(final_price) as sum_stock_spend'))->first();
        
        $recieve = DistributionPayment::whereHas('admin_order',function($query){
            return $query->where('created_by',Auth::id())->where('is_cancelled',0);
        })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        
        $expense = Expense::select(DB::raw('SUM(amount) as sum_expense'))->where('created_by',Auth::id())->first();
        return view('charts.index',compact('stock_added','recieve','expense'));
    }

    public function download_pdf(){
        $stock_added = StockHistory::select(DB::raw('SUM(final_price) as sum_stock_spend'))->first();
        
        $recieve = DistributionPayment::whereHas('admin_order',function($query){
            return $query->where('created_by',Auth::id())->where('is_cancelled',0);
        })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        
        $expense = Expense::select(DB::raw('SUM(amount) as sum_expense'))->where('created_by',Auth::id())->first();
        return view('charts.chartpdf',compact('stock_added','recieve','expense'));
  
    }
}
