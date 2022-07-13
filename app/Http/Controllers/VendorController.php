<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Users;
use App\Models\State;
use Auth;
use DB;
use Validator;
use Image;
use PDF;


class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::all();
        $states = State::all();
        return view('vendor.index',compact('vendors','states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::all();
        return view('vendor.create',compact('states'));
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
                
            $validation = Validator::make($input,[
                'name'             => ['required', 'string', 'max:255'],
                'email'            => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
                'phone'           => ['required','unique:vendors'],
                'firm_name'        => ['required'],
                'state'            => ['required'],
                'district'         => ['required'],
                
            ],[
               
                'name.required'          => 'Name field is required',
                'name.string'            => 'Name field can only accept string',
                'name.max'               => 'Name field max length is 255',
                'email.required'         => 'Email field is required',
                'email.string'            => 'Email field can only accept string',
                'email.max'               => 'Email field max length is 255',
                'mobile.required'         => 'Mobile field is required',
                'firm_name.required'         => 'Firm Name field is required',
                'gst_no.required'         => 'GST field is required',
                'state.required'         => 'State field is required',
                'district.required'         => 'District field is required',
                
            ]);

            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                    
                }
                
                return back()->with('error',$message);
                
            }

            DB::beginTransaction();
            $vendors = new Vendor();
    
            $input['created_by'] = Auth::id();
            $vendors->create($input);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return back()->with('success','Vendor is created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        echo json_encode(compact('vendor'));
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
        try {

            $input = $request->all();
                
            $validation = Validator::make($input,[
                'name'             => ['required', 'string', 'max:255'],
                'email'            => ['required', 'string', 'email', 'max:255', 'unique:vendors,email,'.$id.',id'],
                'phone'           => ['required','unique:vendors,phone,'.$id.',id'],
                'firm_name'        => ['required'],
                
                'state'         => ['required'],
                'district'         => ['required'],
                
            ],[
               
                'name.required'          => 'Name field is required',
                'name.string'            => 'Name field can only accept string',
                'name.max'               => 'Name field max length is 255',
                'email.required'         => 'Email field is required',
                'email.string'            => 'Email field can only accept string',
                'email.max'               => 'Email field max length is 255',
                'mobile.required'         => 'Mobile field is required',
                'firm_name.required'         => 'Firm Name field is required',
                'gst_no.required'         => 'Gst field is required',
                'state.required'         => 'State field is required',
                'district.required'         => 'District field is required',
                
            ]);

            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                    
                }
                
                return back()->with('error',$message);
                
            }

            DB::beginTransaction();
            $vendors = Vendor::findOrFail($id);
    
            $input['updated_by'] = Auth::id();
            $vendors->update($input);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return back()->with('success','Vendor is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Vendor::findOrFail($id);

        if(count($user->stocks)>0){
            return back()->with('warning','Stocks are assigned under this vendor, cannot delete!!');
        }
        $value = $user->delete();

        if ($value) {
            return back()->with('success','Vendor Has Been Deleted');
        }
    }

    public function export_table($type){
        $outcolumn = [
            'Id','Name','Email','Mobile','Firm Name','GST Number','State', 'District', 'Address'
        ];

        $column =['vendors.id','vendors.name','vendors.email','vendors.phone','vendors.firm_name','vendors.gst_no','states.name as state','districts.name as district','vendors.address'];
        
        $users = Vendor::leftjoin('states','states.id','vendors.state')
        ->leftjoin('districts','districts.id','vendors.district')->select($column)->get()->toArray();

        if($type == 'excel'){

            $file_name = 'vendors_excel_'.strtotime('now').'.csv';
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=".$file_name);
            
            $fp = fopen('php://output', 'w');
            
            if ($fp) {
                
                $tmpArray = $outcolumn;
                fputcsv($fp, $tmpArray);
                
                
                foreach($users as $key => $data) {
                    
                    $tmpArray = $data;
                            
                    fputcsv($fp, $tmpArray);
                }
                
            }   
            
            fclose($fp);

        }else if($type == 'pdf'){

            $data = array(
                "table_data" => $users
            );
            $pdfFilePath = "vendors_pdf_".strtotime('now').".pdf";
            $pdf = PDF::loadView('pdf.vendortemplate',$data);
            return $pdf->stream($pdfFilePath);
            
        }
    }
}
