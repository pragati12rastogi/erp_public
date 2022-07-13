@extends('layouts.master')
@section('title', 'Category Creation')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            $("#category_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 3 ] 
                    }
                },
                {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1,3 ] //Your Column value those you want
                    }
                }
                
                ]

            });

            jQuery('#category_form').validate({ // initialize the plugin
                rules: {

                    
                    name:{
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

            @foreach($categories as $cat)
                jQuery('#category_form_{{$cat->id}}').validate({ // initialize the plugin
                    rules: {
                        name:{
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
            @endforeach
        });
        
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        @include('flash-msg')
    </div>
</div>
@include('category.list')
@include('category.create')
@foreach($categories as $cat)
    <div id="{{$cat->id}}_cat" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this category? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/category/'.$cat->id)}}" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    
    @include('category.edit')
    
@endforeach

@endsection