<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Category;
use App\Models\Item;
use App\Models\Vendor;
use App\Models\User;
use App\Custom\Constants;
use App\Models\StockHistory;
use App\Models\Stock;
use App\Models\DistributionPayment;
use App\Models\Expense;
use App\Models\PurchaseOrder;
use App\Models\Task;
use App\Models\Hsn;
use App\Models\Lead;
use App\Models\UserStock;
use App\Models\UserStockDistributionOrder;
use App\Models\Announcement;
use App\Models\DismissedAnnouncement;

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
    public function index()
    {
        $total_category = Category::get()->count();
        $total_items = Item::get()->count();
        $total_vendor = Vendor::get()->count();
        $total_user = User::whereHas('role',function($query){
            $query->where('name','<>',Constants::ROLE_ADMIN);
        })->get()->count();

        $stock_added = StockHistory::select(DB::raw('SUM(final_price) as sum_stock_spend'))->first()->sum_stock_spend;
        
        $recieve = DistributionPayment::whereHas('admin_order',function($query){
            return $query->where('created_by',Auth::id())->where('is_cancelled',0);
        })->select(DB::raw('IFNULL(SUM(amount),0) as sum_recieve'))->first()->sum_recieve;
        
        $expense = Expense::select(DB::raw('IFNULL(SUM(amount),0) as sum_expense'))->where('created_by',Auth::id())->first()->sum_expense;
        
        $purchase_order = PurchaseOrder::select(DB::raw('DATE_FORMAT(created_at,"%b") as month'),DB::raw('SUM(total_cost) as total'))
        ->whereRaw('YEAR(created_at)=YEAR(NOW())')
        ->groupBy(DB::raw('YEAR(created_at), DATE_FORMAT(created_at,"%b")'))->get()->toArray();
        $pur_month = array_column($purchase_order,'month');
        $pur_total = array_column($purchase_order,'total');
        $graph_pur = array_combine($pur_month,$pur_total);

        $total_hsn = Hsn::get()->count();
        $total_user_items = UserStock::where('user_id',Auth::id())->get()->count();

        // local stock summary
        $ls_sell = UserStockDistributionOrder::where('is_cancelled',0)->where('created_by',Auth::id())->select(DB::raw('IFNULL(SUM(total_cost),0) as sum_sell'))->first()->sum_sell;
        $ls_recieve = DistributionPayment::whereHas('local_order',function($query){
            return $query->where('created_by',Auth::id())->where('is_cancelled',0);
        })->select(DB::raw('IFNULL(SUM(amount),0) as sum_recieve'))->first()->sum_recieve;
        $ls_balance = $ls_sell-$ls_recieve;

        $tasks = Task::whereRaw("Find_in_set(".Auth::id().",REPLACE(REPLACE(REPLACE(assigned_to,'[',''),']',''),'\"',''))")->get();

        $leads = Lead::select(DB::raw('DATE_FORMAT(created_at,"%b") as month'),DB::raw('Count(id) as total'))
        ->whereRaw('YEAR(created_at)=YEAR(NOW())')
        ->groupBy(DB::raw('YEAR(created_at), DATE_FORMAT(created_at,"%b")'))->get()->toArray();
        $leads_month = array_column($leads,'month');
        $leads_total = array_column($leads,'total');
        $graph_leads = array_combine($leads_month,$leads_total);
        
        $announcement = Announcement::whereRaw("Find_in_set(".Auth::id().",REPLACE(REPLACE(REPLACE(announcements.user_ids,'[',''),']',''),'\"',''))")->whereDoesntHave('dismissed',function($query){
            return $query->where('user_id',Auth::id());
        })->get()->toArray();

        return view('home',compact('total_category','total_items','total_vendor','total_user','stock_added','recieve','expense','graph_pur','total_hsn','total_user_items','ls_sell','ls_recieve','ls_balance','tasks','graph_leads','announcement'));
    }
}
