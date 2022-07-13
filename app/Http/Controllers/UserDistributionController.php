<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserStock;
use App\Models\UserStockDistributionItem;
use App\Models\UserStockDistributionOrder;
use App\Models\DistributionOrder;
use App\Models\DistributionPayment;
use App\Models\RequestProduct;
use App\Models\User;
use App\Models\Item;
use App\Notifications\ProductRequestNotification;
use Crypt;
use App\Custom\Constants;
use DB;
use Auth;
use Validator;


class UserDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $given_distribution = UserStockDistributionOrder::where('created_by',Auth::id())->get();
        
        $sell = UserStockDistributionOrder::where('is_cancelled',0)->where('created_by',Auth::id())->select(DB::raw('SUM(total_cost) as sum_sell'))->first();
        $recieve = DistributionPayment::whereHas('local_order',function($query){
            return $query->where('created_by',Auth::id())->where('is_cancelled',0);
        })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        $items = UserStock::where('user_id',Auth::id())->get();
        return view('user_stock.distribution_index',compact('given_distribution','sell','recieve','items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = UserStock::where('user_id',Auth::id())->get();
        return view('user_stock.local_distribution_creation',compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            
            $validate = Validator::make($input ,[
                'user_name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'item.*' => 'required',
                
            ],[
                'user_name.required' => 'User Name is required',
                'phone.required' => 'Mobile Number is required',
                'address.required' => 'Address is required',
                'item.*.required' => 'No product selected',
            ]);

            if($validate->fails()){
                $validation_arr = $validate->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                return back()->with('error',$message);
            }
            DB::beginTransaction();
            
            $gen_invoice_no = generate_invoice_no();
            $check_userinvoice_no = UserStockDistributionOrder::where('invoice_no',$gen_invoice_no)->first();
            
            if(!empty($check_userinvoice_no)){
                return back()->with('error','Generated Invoice number is already taken. Change sequence from master.');
            }
            
            $timestamp = date('Y-m-d H:i:s');

            $invoice =[];
            $total_tax = 0;
            $total_cost = 0;
            $total_discount = 0;

            $count_gst_types = count($input['gst_types']);

            foreach($input['item'] as $userstock_id => $qty){
                $stock = UserStock::with('item.gst_percent')->where('id',$userstock_id)->first();
                $item_id = $stock['item_id'];
                if($qty > $stock['prod_quantity']){
                    DB::rollback();
                    return back()->with('error',$stock->item->name ." Quantity is not sufficient");
                }
                $price_qty = $stock['price'] * $qty;
                
                $invoice[$item_id]['product_price'] = $stock['price'];
                $invoice[$item_id]['distributed_quantity'] = $qty;
                $discount = $input['discount'][$userstock_id];

                // $tax = ($price_qty * $stock->item->gst_percent->percent)/100;
                $percent =100;
                $gst_db = $stock->item->gst_percent->percent;
                $percent_and_gst = $percent + $gst_db;
                $tax = $price_qty / $percent_and_gst * $gst_db;

                // $invoice[$item_id]['gst'] = $tax;

                if(in_array('sgst',$input['gst_types'])){ $invoice[$item_id]['sgst'] = round($tax/$count_gst_types,2);}
                if(in_array('cgst',$input['gst_types'])){ $invoice[$item_id]['cgst'] = round($tax/$count_gst_types,2);}
                if(in_array('igst',$input['gst_types'])){ $invoice[$item_id]['igst'] = round($tax/$count_gst_types,2);}

                $total_tax += round($tax,2);
                $total_cost += round($price_qty-$discount,2);
                $total_discount += round($discount,2);

                $invoice[$item_id]['product_total_price'] = $price_qty;
                $invoice[$item_id]['discount'] = $discount;
                $stock->decrement('prod_quantity',$qty);
            }
            
            $order = new UserStockDistributionOrder();
            $order->invoice_no = $gen_invoice_no;
            $order->user_name    = $input['user_name'];
            $order->address    = $input['address'];
            $order->phone    = $input['phone'];
            $order->total_cost = $total_cost;
            $order->total_tax  = $total_tax;
            $order->total_discount    = $total_discount;
            $order->created_by = Auth::id();
            $order->created_at = $timestamp;
            $order->save();
            // order created

            foreach($invoice as $item_id => $item_data){
                $invoice_insert = new UserStockDistributionItem();
                $invoice_insert->order_id             = $order->id;
                $invoice_insert->item_id              = $item_id;
                $invoice_insert->product_price        = $item_data['product_price'];

                if(isset($item_data['sgst'])){
                    $invoice_insert->sgst            = $item_data['sgst'];
                }
                if(isset($item_data['cgst'])){
                    $invoice_insert->cgst            = $item_data['cgst'];
                }
                if(isset($item_data['igst'])){
                    $invoice_insert->igst             =  $item_data['igst'];
                }

                $invoice_insert->distributed_quantity = $item_data['distributed_quantity'];
                $invoice_insert->discount             = $item_data['discount'];
                $invoice_insert->product_total_price  = $item_data['product_total_price'];
                $invoice_insert->created_at = $timestamp;
                $invoice_insert->save();
            }

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Local Distribution done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dis = UserStockDistributionOrder::findOrFail($id);
        $sell = UserStockDistributionOrder::where('is_cancelled',0)->where('id',$id)->select(DB::raw('SUM(total_cost) as sum_sell'))->first();
        $recieve = DistributionPayment::whereHas('local_order',function($query)use($id){
            return $query->where('id',$id)->where('is_cancelled',0);
        })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        return view('user_stock.show',compact('dis','sell','recieve'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dis = UserStockDistributionOrder::findOrFail($id);
        foreach($dis->items as $ind => $item){
            $user_stock = UserStock::where('user_id',$dis['created_by'])->where('item_id',$item['item_id'])->first();
            $user_stock->increment('prod_quantity',$item['distributed_quantity']);
        }
        
        $dis->update([
            'is_cancelled'=>1,
            'updated_by'=> Auth::id()
        ]);

        return back()->with('success','Local Distribution Cancelled');
    }


    public function users_stock_list(){
        $user_stock = UserStock::where('user_id',Auth::id())->get();
        return view('user_stock.stock_list',compact('user_stock'));
    }

    public function print_invoice($id){
        
        $dis = UserStockDistributionOrder::findOrFail($id);
        return view('user_stock.invoice',compact('dis'));
    }


    public function print_single_invoice($id){
        
        $dis = UserStockDistributionItem::findOrFail($id);
        
        return view('user_stock.singleinvoice',compact('dis'));
    }

    public function distribution_payment(Request $request){
        $input = $request->all();
        
        $id = Crypt::decrypt($input['local_order_id']);
        if(empty($id)){
            return back()->with('error','some error occoured order id not find');
        }else{

            $order = UserStockDistributionOrder::find($id);
            $calculate = DistributionPayment::where('local_order_id',$id)->select(DB::raw('SUM(amount) as total'))->first();

            if(empty($calculate['total'])){
                if($order['total_cost']< $input['amount']){
                    return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
                }
            }else{
                $new_pay = $calculate['total']+$input['amount'];
                if($order['total_cost']< $new_pay){
                    return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
                }
            }
            
            $input['local_order_id'] = $id;
            $input['created_by'] = Auth::id();

            $distribution  = new DistributionPayment();
            $distribution->create($input);
            return back()->with('success',"Payment Amount updated");
        }

    }

    public function request_product_from_admin(Request $request){
    
        try{
            $input = $request->all();
            $validate = Validator::make($input,[
                'item_id' => 'required',
                'quantity'=> 'required'
            ],[
                'item_id.required' => 'Item not found',
                'quantity.required' => 'Quantity not mentioned'
            ]);

            if($validate->fails()){
                $validation_arr = $validate->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                return back()->with('error',$message);
            }
            DB::beginTransaction();
            
            $item = Item::findOrFail($input['item_id']);
            $input['requested_by'] = Auth::id();
            $requested_prod =  RequestProduct::create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();
        $item_name = $item->name;
        $item_qty = $input['quantity'];
        $distributer_name = Auth::user()->name;
        $url = url('stock-distributions');
        $get_admins = getUsers(Constants::ROLE_ADMIN);
        
        \Notification::send($get_admins, new ProductRequestNotification($item_name,$item_qty, $distributer_name,$url));
        return back()->with('success','Request sent to admin');

    }

    public function payment_gateway(Request $request,$user_id,$order_id){
        $new_amt = $request->amount;
        
        $order_id = Crypt::decrypt($order_id);
        $order = UserStockDistributionOrder::findOrFail($order_id);
        $calculate = DistributionPayment::where('local_order_id',$order_id)->select(DB::raw('SUM(amount) as total'))->first();
        $url = route('local-distribution.razorpay.payment.store',[$user_id,Crypt::encrypt($order_id)]);

        return view('payment.page',compact('user_id','order_id','new_amt','order','url'));
    }
}
