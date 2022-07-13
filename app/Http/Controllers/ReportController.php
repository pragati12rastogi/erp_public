<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistributionOrder;
use App\Models\PurchaseOrder;
use App\Models\DistributionPayment;
use App\Models\Distribution;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Role;
use App\Models\User;
use DB;

class ReportController extends Controller
{
    public function salesReportIndex(){
        $sale_agents = User::all();
        return view('reports.sales',compact('sale_agents'));
    }

    public function salesInvoiceReportsApi(Request $request){

        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $sale_agent = $request->input('sale_agent');
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $invoice = DistributionOrder::leftjoin('users','users.id','distribution_orders.user_id')
        ->select('distribution_orders.id','distribution_orders.invoice_no','distribution_orders.total_cost','distribution_orders.total_discount','distribution_orders.total_tax','users.name',DB::raw('DATE_FORMAT(distribution_orders.created_at,"%d-%m-%Y %H:%m") as created_date'),DB::raw('(distribution_orders.total_cost + distribution_orders.total_discount)-distribution_orders.total_tax as amount'))->where('is_cancelled',0);
        
        if(!empty($s_date) && empty($e_date))
        {
            $invoice->where(function($query) use ($s_date){
                $query->where('distribution_orders.created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $invoice->where(function($query) use ($e_date){
                $query->where('distribution_orders.created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $invoice->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('distribution_orders.created_at',array($s_date,$e_date));
            });   
        }

        if(!empty($sale_agent)){
            $invoice->where(function($query) use ($sale_agent){
                $query->where('distribution_orders.created_by',$sale_agent);
            });
        }

        if(!empty($serach_value))
        {
            $invoice->where(function($query) use ($serach_value){
                $query->where('distribution_orders.invoice_no','LIKE',"%".$serach_value."%")
                ->orwhere('name','like',"%".$serach_value."%")
                ->orwhere('distribution_orders.total_cost','like',"%".$serach_value."%")
                ->orwhere('distribution_orders.total_discount','like',"%".$serach_value."%")
                ->orwhere('distribution_orders.total_tax','like',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $invoice->get()->count();
        $invoice = $invoice->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['invoice_no','name','created_at','amount','total_tax','total_discount','total_cost'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $invoice->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $invoice->orderBy('id','desc');
        }

        $invoices_data = $invoice->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $invoices_data; 
        
        return json_encode($array);

    }

    public function salesItemReportsApi(Request $request){

        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $sale_agent = $request->input('sale_agent');
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $invoice = Stock::leftjoin('items','items.id','stocks.item_id')
        ->leftjoin('distributions','distributions.item_id','stocks.item_id')
        ->select('stocks.id','items.name',DB::raw('SUM(distributions.product_total_price) as total_amt'),DB::raw('SUM(distributions.distributed_quantity) as qty_sold'),DB::raw('SUM(distributions.product_total_price)/COUNT(distributions.id) as avg_amt'))->groupBy('stocks.id');
        
        if(!empty($s_date) && empty($e_date))
        {
            $invoice->where(function($query) use ($s_date){
                $query->where('distributions.created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $invoice->where(function($query) use ($e_date){
                $query->where('distributions.created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $invoice->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('distributions.created_at',array($s_date,$e_date));
            });   
        }

        if(!empty($sale_agent)){
            $invoice->where(function($query) use ($sale_agent){
                $query->where('items.created_by',$sale_agent);
            });
        }

        if(!empty($serach_value))
        {
            $invoice->where(function($query) use ($serach_value){
                $query->where('items.name','LIKE',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $invoice->get()->count();
        $invoice = $invoice->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['items.name','qty_sold','total_amt','avg_amt'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $invoice->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $invoice->orderBy('id','desc');
        }

        $invoices_data = $invoice->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $invoices_data; 
        
        return json_encode($array);

    }

    public function salesPaymentReportsApi(Request $request){

        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $invoice = DistributionPayment::leftjoin('distribution_orders','distribution_orders.id','distribution_payments.admin_order_id')
        ->leftjoin('users','users.id','distribution_orders.user_id')
        ->select('distribution_payments.id','distribution_payments.amount','distribution_payments.transaction_type','distribution_payments.transaction_id','distribution_orders.invoice_no',DB::raw('DATE_FORMAT(distribution_payments.created_at,"%d-%m-%Y") as created_date'),'users.name')->where('admin_order_id','!=',null);
        
        if(!empty($s_date) && empty($e_date))
        {
            $invoice->where(function($query) use ($s_date){
                $query->where('distribution_payments.created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $invoice->where(function($query) use ($e_date){
                $query->where('distribution_payments.created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $invoice->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('distribution_payments.created_at',array($s_date,$e_date));
            });   
        }

        

        if(!empty($serach_value))
        {
            $invoice->where(function($query) use ($serach_value){
                $query->where('distribution_payments.amount','LIKE',"%".$serach_value."%")
                ->orWhere('distribution_payments.transaction_type','LIKE',"%".$serach_value."%")
                ->orWhere('distribution_payments.transaction_id','LIKE',"%".$serach_value."%")
                ->orWhere('distribution_orders.invoice_no','LIKE',"%".$serach_value."%")
                ->orWhere('users.name','LIKE',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $invoice->get()->count();
        $invoice = $invoice->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['distribution_payments.id','created_date','distribution_orders.invoice_no','users.name','transaction_type','transaction_id','distribution_payments.amount'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $invoice->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $invoice->orderBy('id','desc');
        }

        $invoices_data = $invoice->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $invoices_data; 
        
        return json_encode($array);

    }

    public function salesPurchaseOrderReportsApi(Request $request){
        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $sale_agent = $request->input('sale_agent');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $invoice = PurchaseOrder::leftjoin('vendors','vendors.id','purchase_orders.vendor_id')
        ->select('purchase_orders.id',DB::raw('CONCAT("PO-INV",LPAD(purchase_orders.id,5,0)) as invoice_no'),DB::raw('DATE_FORMAT(purchase_orders.created_at,"%d-%m-%Y %H:%m") as created_date'),'vendors.name','purchase_orders.total_cost','purchase_orders.total_tax',DB::raw('(purchase_orders.total_cost - purchase_orders.total_tax) as amount'))->where('is_cancelled',0);
        
        if(!empty($s_date) && empty($e_date))
        {
            $invoice->where(function($query) use ($s_date){
                $query->where('purchase_orders.created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $invoice->where(function($query) use ($e_date){
                $query->where('purchase_orders.created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $invoice->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('purchase_orders.created_at',array($s_date,$e_date));
            });   
        }

        if(!empty($sale_agent)){
            $invoice->where(function($query) use ($sale_agent){
                $query->where('purchase_orders.created_by',$sale_agent);
            });
        }
        

        if(!empty($serach_value))
        {
            $invoice->where(function($query) use ($serach_value){
                $query->where('vendors.name','LIKE',"%".$serach_value."%")
                ->orWhere('purchase_orders.total_cost','LIKE',"%".$serach_value."%")
                ->orWhere(DB::raw('CONCAT("PO-INV",LPAD(purchase_orders.id,5,0))'),'LIKE',"%".$serach_value."%")
                ->orWhere('purchase_orders.total_tax','LIKE',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $invoice->get()->count();
        $invoice = $invoice->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['purchase_orders.id','created_date','vendors.name','users.name','total_cost','amount','total_tax'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $invoice->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $invoice->orderBy('purchase_orders.id','desc');
        }

        $invoices_data = $invoice->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $invoices_data; 
        
        return json_encode($array);
    }
}
