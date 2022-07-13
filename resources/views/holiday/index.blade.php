@extends('layouts.master')
@section('title', 'Holiday Summary')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {

            $("#holiday_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2 ] 
                    }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2 ] //Your Column value those you want
                        }
                    }
                ]
            });
            $('form.validateForm').each(function(key, form) {
                jQuery(form).validate({
                    // initialize the plugin
                    rules: {

                        name:{
                            required:true,
                        },
                        date:{
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

        });
        
        function edit_modal(edit_id){
            var submit_edit_url = '{{url("holiday")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        var inputs = result.holiday;
                        $('#holiday_form_upd').attr('action',submit_edit_url);
                        $('#name_upd').val(inputs.name);
                        $('#date_upd').val(inputs.date);
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
            var delete_url = "{{url('holiday')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 ">
      
        @include('flash-msg')
      
    </div>
</div>

@include('holiday.list')

@include('holiday.form')

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

@endsection