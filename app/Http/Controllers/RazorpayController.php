<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use DB;
use Crypt;
use App\Models\DistributionOrder;
use App\Models\UserStockDistributionOrder;
use App\Models\DistributionPayment;

class RazorpayController extends Controller
{
    public function stockDistributionStore(Request $request, $user_id, $order_id){
        $input = $request->all();
        
        $order_id = Crypt::decrypt($order_id);
        $order = DistributionOrder::findOrFail($order_id);
        $calculate = DistributionPayment::where('admin_order_id',$order_id)->select(DB::raw('SUM(amount) as total'))->first();

        $new_amt = $order['total_cost']-$calculate['total'];
        if(empty($calculate['total'])){
            if($order['total_cost']< $input['actualtotal']){
                return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
            }
        }else{
            $new_pay = $calculate['total']+$input['actualtotal'];
            if($order['total_cost']< $new_pay){
                return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
            }
        }

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
  
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(!empty($input['razorpay_payment_id'])){
            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
                
                $txn_id = $payment->id;

                $payment_status = 1;

                $distribution  = new DistributionPayment();
                $distribution->create([
                    'admin_order_id'=>$order_id,
                    'amount'=> $input['actualtotal'],
                    'transaction_type'=>'online',
                    'transaction_id'=>$txn_id,
                    'created_by'=>$user_id
                ]);
                
                return redirect('/')->with('success','payment done');
            } catch (Exception $e) {
                return redirect()->back()->with('error',$e->getMessage());
            }
        }
    }

    public function localDistributionStore(Request $request, $user_id, $order_id){
        $input = $request->all();
        
        $order_id = Crypt::decrypt($order_id);
        $order = UserStockDistributionOrder::findOrFail($order_id);
        $calculate = DistributionPayment::where('local_order_id',$order_id)->select(DB::raw('SUM(amount) as total'))->first();

        $new_amt = $order['total_cost']-$calculate['total'];
        if(empty($calculate['total'])){
            if($order['total_cost']< $input['actualtotal']){
                return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
            }
        }else{
            $new_pay = $calculate['total']+$input['actualtotal'];
            if($order['total_cost']< $new_pay){
                return back()->with('error','Wrong entry, Pending amount is lesser than what you enter');
            }
        }
        
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
  
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(!empty($input['razorpay_payment_id'])){
            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
                
                $txn_id = $payment->id;

                $payment_status = 1;

                $distribution  = new DistributionPayment();
                $distribution->create([
                    'local_order_id'=>$order_id,
                    'amount'=> $input['actualtotal'],
                    'transaction_type'=>'online',
                    'transaction_id'=>$txn_id,
                    'created_by'=>$user_id
                ]);
                
                return redirect('/')->with('success','payment done');
            } catch (Exception $e) {
                return redirect()->back()->with('error',$e->getMessage());
            }
        }
    }
}
