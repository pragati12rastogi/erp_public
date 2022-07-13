<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribution;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use App\Custom\Constants;
use App\Models\Stock;
use App\Models\DistributionOrder;
use App\Models\BillingSetting;
use App\Models\DistributionPayment;
use App\Models\UserStock;
use App\Models\ProductCharge;
use DB;
use Crypt;
use Auth;
use Validator;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(is_admin(Auth::user()->role_id)){
            $distribution = DistributionOrder::get();

            $sell = DistributionOrder::where('is_cancelled',0)->where('created_by',Auth::id())->select(DB::raw('SUM(total_cost) as sum_sell'))->first();
            
            $recieve = DistributionPayment::whereHas('admin_order',function($query){
                return $query->where('created_by',Auth::id())->where('is_cancelled',0);
            })->select(DB::raw('SUM(amount) as sum_recieve'))->first();

        }else{
            $distribution = DistributionOrder::where('user_id',Auth::id())->Orwhere('created_by',Auth::id())->get();

            $sell = DistributionOrder::where('is_cancelled',0)->where('user_id',Auth::id())->select(DB::raw('SUM(total_cost) as sum_sell'))->first();
            
            $recieve = DistributionPayment::whereHas('admin_order',function($query){
                return $query->where('user_id',Auth::id())->where('is_cancelled',0);
            })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        }
        
        $items = Item::whereHas('stock')->get();
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        $user = User::all();
        return view('distributer.index',compact('distribution','sell','recieve','user','roles','items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::whereHas('stock')->get();
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        $user = User::all();
        return view('distributer.create',compact('user','roles','items'));
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
                'role_id' => 'required',
                'user_id' => 'required',
                'item.*' => 'required',
                'gst_types.*'=>'required',
                
            ],[
                'role_id.required' => 'Role is required',
                'user_id.required' => 'Please select User',
                'item.*.required' => 'No product selected',
                'gst_types.*.required' => 'No Gst type selected',
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
            $check_invoice_no = DistributionOrder::where('invoice_no',$gen_invoice_no)->first();
            if(!empty($check_invoice_no)){
                return back()->with('error','Generated Invoice number is already taken. Change sequence from master.');
            }

            $billing_user = User::where('id',$input['user_id'])->first();

            $timestamp = date('Y-m-d H:i:s');

            $seller_user = Auth::user()->state_id;
            
            $invoice =[];
            $total_tax = 0;
            $total_cost = 0;
            $total_discount = 0;

            $count_gst_types = count($input['gst_types']);
            foreach($input['item'] as $item_id => $qty){
                $stock = Stock::with('item.gst_percent')->where('item_id',$item_id)->first();

                $prod_charge = ProductCharge::where(['state_id'=>$billing_user->state_id,'district_id'=>$billing_user->district,'area_id'=>$billing_user->area_id])->where('product_id',$item_id)->first();

                $singlecharge = 0;
                $charge = 0;
                if(!empty($prod_charge)){
                    $singlecharge = $prod_charge['charges'];
                    $charge = $prod_charge['charges'] * $qty;
                }
                
                $price_with_charge = $stock['price_for_user'] + $singlecharge;
                $price_qty = $price_with_charge * $qty;
                $discount = $input['discount'][$item_id];

                $invoice[$item_id]['product_price'] = $price_with_charge;
                $invoice[$item_id]['distributed_quantity'] = $qty;
                $invoice[$item_id]['charge'] = $singlecharge;
                $invoice[$item_id]['gst_percent'] = $stock->item->gst_percent->percent;

                // If tax is already added in amount
                $percent =100;
                $gst_db = $stock->item->gst_percent->percent;
                $percent_and_gst = $percent + $gst_db;
                $tax = $price_qty / $percent_and_gst * $gst_db;
                
                $total_tax += round($tax,2);
                $total_cost += round($price_qty-$discount,2);
                $total_discount += round($discount,2);

                /* if($billing_user['state_id'] == $seller_user){
                    // scgst
                    $invoice[$item_id]['scgst'] = round($tax/2,2);
                }else{
                    // igst
                    $invoice[$item_id]['igst'] = round($tax,2);
                } */

                if(in_array('sgst',$input['gst_types'])){ $invoice[$item_id]['sgst'] = round($tax/$count_gst_types,2);}
                if(in_array('cgst',$input['gst_types'])){ $invoice[$item_id]['cgst'] = round($tax/$count_gst_types,2);}
                if(in_array('igst',$input['gst_types'])){ $invoice[$item_id]['igst'] = round($tax/$count_gst_types,2);}

                $invoice[$item_id]['product_total_price'] = $price_qty;
                $invoice[$item_id]['discount'] = $discount;
                $stock->decrement('prod_quantity',$qty);
            }
            
            

            $order = new DistributionOrder();
            $order->invoice_no        = $gen_invoice_no;
            $order->role_id           = $input['role_id'];
            $order->user_id           = $input['user_id'];
            $order->total_cost        = $total_cost;
            $order->total_tax         = $total_tax;
            $order->total_discount    = $total_discount;
            $order->created_by        = Auth::id();
            $order->created_at        = $timestamp;
            $order->save();
            // order created

            foreach($invoice as $item_id => $item_data){
                $invoice_insert = new Distribution();
                
                $invoice_insert->order_id             = $order->id;
                $invoice_insert->item_id              = $item_id;
                $invoice_insert->product_price        = $item_data['product_price'];
                $invoice_insert->charge               = $item_data['charge'];
                $invoice_insert->gst_percent          = $item_data['gst_percent'];
                $invoice_insert->user_id              = $input['user_id'];
                $invoice_insert->distributed_quantity = $item_data['distributed_quantity'];
                
                if(isset($item_data['sgst'])){
                    $invoice_insert->sgst            = $item_data['sgst'];
                }
                if(isset($item_data['cgst'])){
                    $invoice_insert->cgst            = $item_data['cgst'];
                }
                if(isset($item_data['igst'])){
                    $invoice_insert->igst             =  $item_data['igst'];
                }
                $invoice_insert->discount             = $item_data['discount'];
                $invoice_insert->product_total_price  = $item_data['product_total_price'];
                $invoice_insert->created_at = $timestamp;
                $invoice_insert->save();
                
                $user_stock = UserStock::updateOrCreate(
                    [
                        'user_id'=>$input['user_id'],'item_id'=>$item_id
                    ],
                    [
                        'prod_quantity'=> DB::raw('prod_quantity + '.$item_data['distributed_quantity']),
                        'price'=> round($item_data['product_price'],2)
                    ]
                );
                
            }

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Distribution done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $dis = DistributionOrder::findOrFail($id);
        $billing_add = BillingSetting::first();
        
        $sell = DistributionOrder::where('is_cancelled',0)->where('id',$id)->select(DB::raw('SUM(total_cost) as sum_sell'))->first();
        $recieve = DistributionPayment::whereHas('admin_order',function($query) use($id) {
            return $query->where('id',$id)->where('is_cancelled',0);
        })->select(DB::raw('SUM(amount) as sum_recieve'))->first();
        
        return view('distributer.show',compact('dis','billing_add','sell','recieve'));
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
        DB::beginTransaction();
        $dis = DistributionOrder::findOrFail($id);

        foreach($dis->invoices as $ind => $item){
            $user_stock = UserStock::where('user_id',$item['user_id'])->where('item_id',$item['item_id'])->first();
            
            if($user_stock['prod_quantity'] < $item['distributed_quantity']){
                DB::rollback();
                return back()->with('error','User Sold some stocks, quantity of product is not available to cancel the product');
            }

            $user_stock->decrement('prod_quantity',$item['distributed_quantity']);

            $stock = Stock::where('item_id',$item['item_id'])->first();
            $stock->increment('prod_quantity',$item['distributed_quantity']);

        }

        $dis->update([
            'is_cancelled'=>1,
            'updated_by'=> Auth::id()
        ]);
        DB::commit();
        return back()->with('success','Stock Distribution Cancelled');

    }

    public function get_user(Request $request){
        $input = $request->all();

        $user = User::where('role_id',$input['role_id'])->get()->toArray();

        echo json_encode(['status'=>'success','data'=>$user]);
    }

    public function get_stock_item_detail(Request $request){
        $stock = Stock::where('item_id',$request->item_id)->first();
        echo json_encode(['status'=>'success','data'=>$stock]);
    }

    public function print_invoice($id){
        
        $dis = DistributionOrder::findOrFail($id);
        $billing_add = BillingSetting::first();
        
        return view('distributer.invoice',compact('dis','billing_add'));
    }


    public function print_single_invoice($id){
        
        $dis = Distribution::findOrFail($id);
        $billing_add = BillingSetting::first();
        
        return view('distributer.singleinvoice',compact('dis','billing_add'));
    }

    public function distribution_payment(Request $request){
        $input = $request->all();
        
        $id = Crypt::decrypt($input['admin_order_id']);
        if(empty($id)){
            return back()->with('error','some error occoured order id not find');
        }else{

            $order = DistributionOrder::find($id);
            $calculate = DistributionPayment::where('admin_order_id',$id)->select(DB::raw('SUM(amount) as total'))->first();

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

            $input['admin_order_id'] = $id;
            $input['created_by'] = Auth::id();

            $distribution  = new DistributionPayment();
            $distribution->create($input);
            return back()->with('success',"Payment Amount updated");
        }

    }

    public function payment_gateway(Request $request,$user_id,$order_id){
        $new_amt = $request->amount;
        
        $order_id = Crypt::decrypt($order_id);
        $order = DistributionOrder::findOrFail($order_id);
        $calculate = DistributionPayment::where('admin_order_id',$order_id)->select(DB::raw('SUM(amount) as total'))->first();
        $url = route('stock-distribution.razorpay.payment.store',[$user_id,Crypt::encrypt($order_id)]);

        return view('payment.page',compact('user_id','order_id','new_amt','order','url'));
    }
}
