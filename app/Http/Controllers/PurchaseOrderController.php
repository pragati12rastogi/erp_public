<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\Item;
use App\Models\Stock;
use DB;
use Auth;
use Validator;
use Mail;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase = PurchaseOrder::get();
        
        $items = Item::whereHas('stock')->get();
        $vendors = Vendor::all();
        return view('purchase_order.index',compact('purchase','items','vendors'));
    
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                'vendor_id' => 'required',
                'item.*' => 'required',
                
            ],[
                'vendor_id.required' => 'Please select Vendor',
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
            
            $billing_user = Vendor::where('id',$input['vendor_id'])->first();

            $timestamp = date('Y-m-d H:i:s');

            $seller_user = Auth::user()->state_id;
            
            $invoice =[];
            $total_tax = 0;
            $total_cost = 0;
            
            $count_gst_types = count($input['gst_types']);

            foreach($input['item'] as $item_id => $qty){
                $stock = Stock::with('item.gst_percent')->where('item_id',$item_id)->first();
                
                $price = $stock['prod_price'];
                $price_qty = $price * $qty;
                
                $invoice[$item_id]['product_price'] = $price;
                $invoice[$item_id]['distributed_quantity'] = $qty;
                $invoice[$item_id]['gst_percent'] = $stock->item->gst_percent->percent;

                // adding tax
                $percent =100;
                $gst_db = $stock->item->gst_percent->percent;
                $tax = $price_qty * ($gst_db/$percent);

                $total_cost += round($price_qty+$tax,2);
                $total_tax += round($tax,2);
                
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

                $invoice[$item_id]['product_total_price'] = $price_qty+$tax;
                
            }
            
            $order = new PurchaseOrder();
            
            $order->vendor_id         = $input['vendor_id'];
            $order->total_cost        = $total_cost;
            $order->total_tax         = $total_tax;
            $order->created_by        = Auth::id();
            $order->created_at        = $timestamp;
            $order->save();
            // order created

            foreach($invoice as $item_id => $item_data){
                $invoice_insert = new PurchaseOrderItem();
                
                $invoice_insert->po_id             = $order->id;
                $invoice_insert->item_id              = $item_id;
                $invoice_insert->product_price        = $item_data['product_price'];
                $invoice_insert->gst_percent          = $item_data['gst_percent'];
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

                $invoice_insert->product_total_price  = $item_data['product_total_price'];
                $invoice_insert->created_at = $timestamp;
                $invoice_insert->save();
                
            }

            
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong: '.$th->getMessage());
        }

        DB::commit();

        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');

        $invoice_order = PurchaseOrder::where('id',$order->id)->first();
        Mail::send('emails.pomail', ['order'=>$invoice_order], function($message) use($from_email,$from_name,$billing_user)
        {
            $message->to($billing_user['email'])->from($from_email)->subject('Purchase Order'.date('d-m-Y H:i'));
        });

        return back()->with('success','Purchase Order done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dis = PurchaseOrder::findOrFail($id);
        
        return view('purchase_order.show',compact('dis'));
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
        //
    }

    public function print_purchase_invoice($id){
        $dis = PurchaseOrder::findOrFail($id);
        
        return view('purchase_order.invoice',compact('dis'));
    }
}
