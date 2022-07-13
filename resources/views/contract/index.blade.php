@extends('layouts.master')
@section('title', 'Contracts')

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
            $("#contracts_table").DataTable({
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

            jQuery('#contracts_form').validate({ // initialize the plugin
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
            var submit_edit_url = '{{url("contract")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        
                        var inputs = result.contract;
                        $('#contracts_form_upd').attr('action',submit_edit_url);
                        $('#user_id_upd').val(inputs.user_id).trigger('change');
                        $('#subject_upd').val(inputs.subject);
                        $('#value_upd').val(inputs.value);
                        $('#type_upd').val(inputs.type).trigger('change');
                        $('#start_date_upd').val(inputs.start_date);
                        $('#end_date_upd').val(inputs.end_date);
                        $('#description_upd').val(inputs.description);

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
            var delete_url = "{{url('contract')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }

        function renew_modal(edit_id){
            var renew_contract = "{{url('renew-contracts')}}/"+edit_id;
            $("#renew_form").attr('action',renew_contract);

            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:renew_contract,
                success:function(result){
                    if(result != ''){
                        var inputs = result.contract;
                        $('#start_date_renew').val(inputs.start_date);
                        $('#end_date_renew').val(inputs.end_date);
                        $('#value_renew').val(inputs.value);
                        $("#renew_table").empty();
                        $str = '';
                        $.each(result.contract_renewal,function(ind,val){
                            $str += '<div class="col-md-12">'+
                                    '<div class="row">'+
                                        '<div class="col-md-10">'+
                                            '<b>'+val.created_by_user.name+' renewed this contract</b><br>'+
                                            '<small class="text-muted text-small">'+val.new_created_at+'</small>'+
                                        '</div>'+
                                        '<div class="col-md-2">'+
                                        '<a href="renew-contracts/delete/'+val.id+'" onclick="return confirm(\'Are you sure you want to delete this?\')"><i class="mdi mdi-close"></i></a>'+
                                        '</div>'+
                                    '</div>'+
                                    '<hr>'+
                                    '<span class="text-reddit text-smaller">New Start Date: '+val.start_date+'</span><br>'+
                                    '<span class="text-reddit text-smaller">New End Date: '+val.end_date+'</span><br>'+
                                    '<span class="text-reddit text-smaller">New Contract Value: '+val.value+'</span><hr>'+
                                '</div>';
                        });
                        $("#renew_table").append($str);
                        $("#renew_modal").modal('show');
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
    <div class="col-md-12">
        @include('flash-msg')
    </div>
</div>
@include('contract.list')
@include('contract.form')

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
        <p>Do you really want to delete this contract? This process cannot be undone.</p>
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