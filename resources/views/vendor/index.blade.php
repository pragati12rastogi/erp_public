@extends('layouts.master')
@section('title', 'Vendor Summary')

@push('style')

@endpush

@push('custom-scripts')
{!! Html::script('/js/area_district.js') !!}
    <script>
        $(function() {
            $("#vendor_table").DataTable();

            jQuery('#vendor_form').validate({ // initialize the plugin
                rules: {

                    
                    name:{
                        required:true,
                    },
                    email:{
                        required:true,
                    },
                    phone:{
                        required:true,
                    },
                    firm_name:{
                        required:true
                    },
                    state:{
                        required:true
                    },
                    district:{
                        required:true
                    },
                    address:{
                        required:false
                    }
                },
                errorPlacement: function(error,element)
                {
                    if($(element).attr('type') == 'radio')
                    {
                        error.insertAfter(element.parent());
                    }
                    else if($(element).is('select'))
                    {
                        error.insertAfter(element.parent());
                    }
                    else{
                        error.insertAfter(element);
                    }
                        
                }
            });

            jQuery('#vendor_form_upd').validate({ // initialize the plugin
                rules: {
                    name:{
                        required:true,
                    },
                    email:{
                        required:true,
                    },
                    phone:{
                        required:true,
                    },
                    firm_name:{
                        required:true
                    },
                    state:{
                        required:true
                    },
                    district:{
                        required:true
                    },
                    address:{
                        required:false
                    }
                },
                errorPlacement: function(error,element)
                {
                    if($(element).attr('type') == 'radio')
                    {
                        error.insertAfter(element.parent());
                    }
                    else if($(element).is('select'))
                    {
                        error.insertAfter(element.parent());
                    }
                    else{
                        error.insertAfter(element);
                    }
                        
                }
            });
        });
        
        function edit_vendor_modal(edit_id){
            var submit_edit_url = '{{url("vendors")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        var inputs = result.vendor;
                        $('#vendor_form_upd').attr('action',submit_edit_url);

                        $('#name_upd').val(inputs.name);
                        $('#email_upd').val(inputs.email);
                        $('#phone_upd').val(inputs.phone);
                        $('#firm_name_upd').val(inputs.firm_name);
                        $('#gst_no_upd').val(inputs.gst_no);
                        $('#address_upd').val(inputs.address);
                        
                        $('#state_id_upd').val(inputs.state).trigger('change');
                        

                        
                        $("#vendor_edit_modal").modal('show');
                        
                        setTimeout(() => {
                            $('#district_id_upd').val(inputs.district).trigger('change')
                        }, 500);

                        

                    }else{
                        alert('some error occured, please refresh page and try again');
                    }
                        
                },
                error:function(error){
                    console.log(error.responseText);
                }
            })
            
        }
    </script>
@endpush

@section('content')

@include('vendor.list')
@include('vendor.create')
@include('vendor.edit')
@foreach($vendors as $vendor)
    <div id="{{$vendor->id}}_vendor" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this vendor? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/vendors/'.$vendor->id)}}" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
  
@endforeach
@endsection