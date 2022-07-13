<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProposalOrder;
use App\Models\ProposalOrderItem;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use App\Models\Stock;
use App\Models\ProductCharge;
use App\Models\BillingSetting;
use App\Models\UserStock;
use App\Models\Distribution;
use App\Models\DistributionOrder;
use App\Custom\Constants;
use DB;
use Validator;
use Auth;

class ProposalController extends Controller
{
    public function index()
    {
        if(is_admin(Auth::user()->role_id)){
            $distribution = ProposalOrder::where('is_cancelled',0)->where('is_converted',0)->get();
            
        }else{
            $distribution = ProposalOrder::where('is_cancelled',0)->where('is_converted',0)->where('user_id',Auth::id())->Orwhere('created_by',Auth::id())->get();

        }
        
        $items = Item::whereHas('stock')->get();
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        $user = User::all();
        return view('proposal_order.index',compact('distribution','user','roles','items'));
    }

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
            
            $statement = DB::select("SHOW TABLE STATUS LIKE 'proposal_orders'");
            $nextId = $statement[0]->Auto_increment;
            $gen_invoice_no = "PPO-INV".str_pad($nextId,5,"0",STR_PAD_LEFT);
            $check_invoice_no = ProposalOrder::where('invoice_no',$gen_invoice_no)->first();
            if(!empty($check_invoice_no)){
                $gen_invoice_no = "PPO-INV".str_pad($nextId+1,5,"0",STR_PAD_LEFT);
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

                if(in_array('sgst',$input['gst_types'])){ $invoice[$item_id]['sgst'] = round($tax/$count_gst_types,2);}
                if(in_array('cgst',$input['gst_types'])){ $invoice[$item_id]['cgst'] = round($tax/$count_gst_types,2);}
                if(in_array('igst',$input['gst_types'])){ $invoice[$item_id]['igst'] = round($tax/$count_gst_types,2);}

                $invoice[$item_id]['product_total_price'] = $price_qty;
                $invoice[$item_id]['discount'] = $discount;
                
            }
            
            

            $order = new ProposalOrder();
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
                $invoice_insert = new ProposalOrderItem();
                
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
                
            }

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Proposal created ');
    }



    public function show($id)
    {   
        $dis = ProposalOrder::findOrFail($id);
        $billing_add = BillingSetting::first();
        
        return view('proposal_order.show',compact('dis','billing_add'));
    }


    public function update(Request $request, $id)
    {
        try {
            
            DB::beginTransaction();

            $input = ProposalOrder::findOrFail($id);
            $proposal_order_item = ProposalOrderItem::where('order_id',$id)->get()->toArray();

            $gen_invoice_no = generate_invoice_no();
            $check_invoice_no = DistributionOrder::where('invoice_no',$gen_invoice_no)->first();
            
            if(!empty($check_invoice_no)){
                return back()->with('error','Generated Invoice number is already taken. Change sequence from master.');
            }

            
            $timestamp = date('Y-m-d H:i:s');

            $invoice =[];
            $total_tax = 0;
            $total_cost = 0;
            $total_discount = 0;

            
            foreach($proposal_order_item as $index => $data){
                $stock = Stock::where('item_id',$data['item_id'])->first();
                $new_qty = $stock['prod_quantity'] - $data['distributed_quantity'];
                if($new_qty>=0){
                    $stock->decrement('prod_quantity',$data['distributed_quantity']);
                }else{
                    return back()->with('error','Stock is not present to accept the convert to invoice request');
                }
                
            }
            
            

            $order = new DistributionOrder();
            $order->invoice_no        = $gen_invoice_no;
            $order->role_id           = $input['role_id'];
            $order->user_id           = $input['user_id'];
            $order->total_cost        = $input['total_cost'];
            $order->total_tax         = $input['total_tax'];
            $order->total_discount    = $input['total_discount'];
            $order->created_by        = $input['created_by'];
            $order->created_at        = $timestamp;
            $order->save();
            // order created

            foreach($proposal_order_item as $item_index => $item_data){
                $invoice_insert = new Distribution();
                
                $invoice_insert->order_id             = $order->id;
                $invoice_insert->item_id              = $item_data['item_id'];
                $invoice_insert->product_price        = $item_data['product_price'];
                $invoice_insert->charge               = $item_data['charge'];
                $invoice_insert->gst_percent          = $item_data['gst_percent'];
                $invoice_insert->user_id              = $item_data['user_id'];
                $invoice_insert->distributed_quantity = $item_data['distributed_quantity'];
                $invoice_insert->sgst                 = $item_data['sgst'];
                $invoice_insert->cgst                 = $item_data['cgst'];
                $invoice_insert->igst                 = $item_data['igst'];
                $invoice_insert->discount             = $item_data['discount'];
                $invoice_insert->product_total_price  = $item_data['product_total_price'];
                $invoice_insert->created_at           = $timestamp;
                $invoice_insert->save();
                
                $user_stock = UserStock::updateOrCreate(
                    [
                        'user_id'=>$input['user_id'],'item_id'=>$item_data['item_id']
                    ],
                    [
                        'prod_quantity'=> DB::raw('prod_quantity + '.$item_data['distributed_quantity']),
                        'price'=> round($item_data['product_price'],2)
                    ]
                );
                
            }
            $input->update(['is_converted'=>1]);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Proposal converted into Distribution ');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $dis = ProposalOrder::findOrFail($id);

        $dis->update([
            'is_cancelled'=>1,
            'updated_by'=> Auth::id()
        ]);
        DB::commit();
        return back()->with('success','Proposal Order Deleted');

    }

    public function print_invoice($id){
        
        $dis = ProposalOrder::findOrFail($id);
        $billing_add = BillingSetting::first();
        
        return view('proposal_order.invoice',compact('dis','billing_add'));
    }
}
