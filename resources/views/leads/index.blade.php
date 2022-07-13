@extends('layouts.master')
@section('title', 'Leads Summary')

@push('style')

@endpush

@push('custom-scripts')
    {!! Html::script('/js/area_district.js') !!}
    <script>
        $(function() {

            $("#lead_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5 ] 
                        }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5 ] //Your Column value those you want
                        }
                    }
                ]
            });

            jQuery('#convert_form').validate({ // initialize the plugin
                rules: {

                    name:{
                        required:true,
                    },
                    email:{
                        required:true,
                    },
                    company:{
                        required:true,
                    },
                    phone:{
                        required:true,
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
        function delete_modal(row_id){
            var delete_url = "{{url('leads/destroy')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }

        function convertToCustomer(lead_id){
            var renew_contract = "{{url('lead-details')}}/"+lead_id;
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:renew_contract,
                success:function(result){
                    if(result != ''){
                        var inputs = result.get_lead;
                        $("#convert_form").attr('action',"{{url('convert-to-customer')}}/"+lead_id);

                        $('#c_name').val(inputs.name).trigger('change');
                        $('#c_email').val(inputs.email);
                        $('#c_phone').val(inputs.phonenumber);
                        $('#c_company').val(inputs.company);
                        $('#c_address').val(inputs.address);
                        $('#c_city').val(inputs.city);
                        $('#c_state').val(inputs.state);
                        $('#c_country').val(inputs.country);
                        $('#c_pincode').val(inputs.pincode);

                        $("#convert_modal").modal('show');
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }
                }
            });
        }

        function edit_modal(edit_id){
            var submit_edit_url = '{{url("leads")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';

            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){
                    if(result != ''){
                        var inputs = result.get_lead;
                        $("#edit_form").attr('action',submit_edit_url);

                        $('#edit_status').val(inputs.status).trigger('change');
                        $('#edit_source').val(inputs.source).trigger('change');
                        $('#edit_assigned_to').val(inputs.assigned_to).trigger('change');
                        $('#edit_name').val(inputs.name);
                        $('#edit_email').val(inputs.email);
                        $('#edit_phonenumber').val(inputs.phonenumber);
                        $('#edit_company').val(inputs.company);
                        $('#edit_address').text(inputs.address);
                        $('#edit_description').text(inputs.description);
                        $('#edit_city').val(inputs.city);
                        $('#edit_state').val(inputs.state);
                        $('#edit_country').val(inputs.country);
                        $('#edit_lead_value').val(inputs.lead_value);
                        $('#edit_pincode').val(inputs.pincode);

                        $("#edit_modal").modal('show');
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }
                }
            });
        }
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 ">
        @include('flash-msg')
    </div>
</div>
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">Leads Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('leads.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-3 text-end" >
              <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Lead")}}</button>
              <a href="{{url('import-leads')}}" class="btn btn-inverse-primary btn-sm">{{__("Import Lead")}}</a>
            </div>
            @endif
            
        </div>
        
        <div class="table-responsive">
          <table id="lead_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Description</th>
                <th>Added on</th>
                @if(Auth::user()->hasPermissionTo('leads.edit') || Auth::user()->hasPermissionTo('leads.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($leads as $key => $d)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$d->name}}</td>
                    <td>{{$d->email}}</td>
                    <td>{{$d->phonenumber}}</td>
                    <td>{{$d->description}}</td>
                    <td>{{date('d-m-Y',strtotime($d->created_at))}}</td>
                    @if(Auth::user()->hasPermissionTo('leads.edit') || Auth::user()->hasPermissionTo('leads.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <td>
                        @if( Auth::user()->hasPermissionTo('leads.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick='delete_modal("{{$d->id}}")' class="btn btn-danger btn-sm text-white">
                                <i class=" mdi mdi-delete-forever"></i>
                            </a>&nbsp;&nbsp;
                        @endif
                        @if( Auth::user()->hasPermissionTo('leads.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                            <a onclick='edit_modal("{{$d->id}}")' class="btn btn-success btn-sm text-white">
                                <i class=" mdi mdi-pen"></i>
                            </a>&nbsp;&nbsp;
                        @endif
                        <a onclick='convertToCustomer("{{$d->id}}")' class="btn btn-warning btn-sm text-white">
                            Convert To Customer
                        </a>
                    </td>
                    @endif
                    
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="delete_modal" class="delete-modal modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
        </div>
        <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this? This process cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <form method="post" id="delete_form" action="" class="pull-right">
            {{csrf_field()}}
            {{method_field("DELETE")}}
    
            <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
            </form>
        </div>
        </div>
    </div>
</div>  

<div id="convert_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Convert To Customer</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="convert_form" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_name" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input type="email" id="c_email" name="email" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Company: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_company" name="company" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Phone: <span class="required">*</span>
                                        </label>
                                        <input type="tel"  name="phone" id="c_phone" minlength="9" maxlength="14" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Address: 
                                        </label>
                                        <textarea id="c_address" name="address" class="form-control" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            City: 
                                        </label>
                                        <input type="text"  name="city" id="c_city"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State: 
                                        </label>
                                        <input type="text"  name="state" id="c_state"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Country: 
                                        </label>
                                        <input type="text"  name="country" id="c_country"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Zipcode: 
                                        </label>
                                        <input type="text"  name="pincode" id="c_pincode"  class="form-control" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Save</button>  
                                <button type="reset" class="btn btn-inverse-dark" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('leads.form')
@endsection