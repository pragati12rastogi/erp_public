@extends('layouts.master')
@section('title', 'Leave Summary')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            
            $("#leave_table").DataTable({
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

                        user_id:{
                            required:true,
                        },
                        from_date:{
                            required:true,
                        },
                        to_date:{
                            required:true
                        },
                        reason:{
                            required:true
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
        
        
        function delete_modal(row_id){
            var delete_url = "{{url('leave')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }

        
        function validateDate(type=""){
            var startDate = new Date($("#from_date"+type).val());
            var endDate = new Date($("#to_date"+type).val());
            
            if (Date.parse(startDate) > Date.parse(endDate)) {
                $("#date_error"+type).text("End date should be greater than Start date");
                $("#to_date"+type).val("");
                event.preventDefault();
                return false;
            }else{
                $("#date_error"+type).text("");
                return true;
            }
        }
    
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 ">
      
        @include('flash-msg')
      
    </div>
</div>

@include('leave.list')

@include('leave.form')

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