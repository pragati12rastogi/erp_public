@extends('layouts.master')
@section('title', 'Expenses Creation')

@push('style')
<style>
    
</style>
@endpush

@push('custom-scripts')
    <script>
        var table;
        const table_index = 3;
        $(function() {
            
            $('form.validateForm').each(function(key, form) {
                
                jQuery(form).validate({ // initialize the plugin
                    rules: {
                        'name[]':{
                            required:true,
                        },
                        'type_amount[]':{
                            required:true,
                            min:0
                        },
                        datetime:{
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


            if(table)
                table.destroy();

            table = $("#expense_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2,3 ] 
                    }
                },
                {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [0, 1, 2,3  ] //Your Column value those you want
                    }
                }
                
                ]
            });
            
        });
        
        function edit_expense_modal(edit_id){
            var submit_edit_url = '{{url("expenses")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        var inputs = result.expense;
                        $('#expense_form_upd').attr('action',submit_edit_url);
                        var str_append = '';
                        var expense_masters = JSON.parse('{!! json_encode($masters) !!}');
                        $(".replicate_div_upd").empty();
                        $.each(inputs.name,function(ind,val){
                            
                            var dd_str = '';
                            $.each(expense_masters,function(ddind,ddval){
                                var selected_ornot = '';
                                if(ddval.id == val){
                                    selected_ornot= 'selected';
                                }
                                dd_str += '<option value="'+ddval.id+'" '+selected_ornot+'> '+ddval.name+' </option>';
                            });
                            if(ind>0){
                                str_append +='<div class="row div_tocopy_upd expense_count_upd appended-content">'+
                                            '<div class="offset-10 col-md-2 text-end"><a href="javascript:void(0)" class="rm-btn text-danger border border-danger"><i class="mdi mdi-close"></i></a></div>'+
                                            '<div class="col-md-6">'+
                                                '<div class="form-group">'+
                                                    '<label class="control-label" for="first-name">Expense Name: <span class="required">*</span></label>'+
                                                    '<select name="name[]" id="name_'+ind+'" class="expname_upd form-control select2" required>'+
                                                        '<option value=""> Select Expense </option>'+dd_str+
                                                    '</select>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-6">'+
                                                '<div class="form-group">'+
                                                    '<label class="control-label" for="first-name">'+
                                                        'Expense Amount: <span class="required">*</span>'+
                                                    '</label>'+
                                                    '<input name="type_amount[]" id="type_amount_'+ind+'_upd" value="'+inputs.type_amount[ind]+'" min="0" type="number" class="form-control" required>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                            }
                            
                        })
                        $(".replicate_div_upd").append(str_append);$('.select2').select2();
                        $('#name_0_upd').val(inputs.name[0]).trigger('change');
                        $('#type_amount_0_upd').val(inputs.type_amount[0]);
                        $('#datetime_upd').val(inputs.datetime);
                        $('#description_upd').text(inputs.description);
                        $("#expense_edit_modal").modal('show');
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }

                },
                error:function(error){
                    console.log(error.responseText);
                }
            })
        }
        $('.date-range-filter').on('change', function () {
          table.draw();
        });

        $(document).on('click','.rm-btn',function(e){
            $(this).parents(".appended-content").remove();
        });

        function addOtherFn(formtype=''){
            var count = $('.expense_count'+formtype).length;
            var expense_masters = JSON.parse('{!! json_encode($masters) !!}');
            var selected_type_values = $('.expname'+formtype).map(function(){return $(this).val();}).get();
            var dd_str = '';
            $.each(expense_masters,function(ind,val){
                if($.inArray((val.id).toString(), selected_type_values) == -1){
                    dd_str += '<option value="'+val.id+'"> '+val.name+' </option>';
                }
            });
            $(".replicate_div"+formtype).append(
                '<div class="row div_tocopy expense_count'+formtype+' appended-content">'+
                    '<div class="offset-10 col-md-2 text-end">'+
                        '<a href="javascript:void(0)" class="rm-btn text-danger border border-danger"><i class="mdi mdi-close"></i></a>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<div class="form-group">'+
                            '<label class="control-label" for="first-name">Expense Name: <span class="required">*</span></label>'+
                            '<select name="name[]" id="name_'+count+formtype+'" class="expname'+formtype+' form-control select2" required>'+
                                '<option value=""> Select Expense </option>'+dd_str+
                            '</select>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<div class="form-group">'+
                            '<label class="control-label" for="first-name">'+
                                'Expense Amount: <span class="required">*</span>'+
                            '</label>'+
                            '<input name="type_amount[]" id="type_amount_'+count+formtype+'" min="0" type="number" class="form-control" required>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            );
            $('.select2').select2();
        }
        
    </script>
    {!! Html::script('/js/datefilter.js') !!}
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 ">
      
        @include('flash-msg')
      
    </div>
</div>

@include('expenses.list')
@include('expenses.create')
@include('expenses.edit')
@foreach($expenses as $e)
    <div id="{{$e->id}}_expense" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this expense? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/expenses/'.$e->id)}}" class="pull-right">
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