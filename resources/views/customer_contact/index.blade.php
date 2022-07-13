@extends('layouts.master')
@section('title', 'Customer Contact')

@push('style')
<style>
    .text-smaller{
        font-size: smaller;
    }
</style>
@endpush

@push('custom-scripts')
    <script>
        $(function() {
            $("#contact_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7,8] 
                    }
                },
                {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2,3,4,5,6,7,8] //Your Column value those you want
                    }
                }
                
                ]

            });

            jQuery('#contact_form').validate({ // initialize the plugin
                rules: {

                    user_id:{
                        required:true,
                    },

                    
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
        
        function edit_modal(edit_id){
            var submit_edit_url = '{{url("customer-contact/".$customer_id)}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        
                        var inputs = result.contact;
                        $('#contact_form_upd').attr('action',submit_edit_url);
                        $('#name_upd').val(inputs.name);
                        $('#email_upd').val(inputs.email);
                        $('#position_upd').val(inputs.position);
                        $('#phone_upd').val(inputs.phone);
                        if(inputs.is_primary){
                            $('#is_primary_upd').attr('checked',true);
                        }
                        
                        $("#edit_modal").modal('show');
                        
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }

                },
                error:function(error){
                    console.log(error.responseText);
                }
            })
        }

        function delete_modal(row_id){
            var delete_url = "{{url('customer-contact/'.$customer_id)}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }

        
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('flash-msg')
    </div>
</div>
@include('customer_contact.list')
@include('customer_contact.form')

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
        <p>Do you really want to delete this contact? This process cannot be undone.</p>
        </div>
        <div class="modal-footer">
        <form method="post" action="" class="pull-right">
                        {{csrf_field()}}
                        {{method_field("DELETE")}}
            <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
            <button type="submit" class="btn btn-danger">Yes</button>
        </form>
        </div>
    </div>
    </div>
</div>
  
@endsection