@extends('layouts.master')
@section('title', 'Item Summary')

@push('style')
  <style>
    .other_image_delete_style{
        position: absolute;
        /* top: 46px;
        left: 12px; */

    }
  </style>
@endpush

@push('custom-scripts')
  <script>
    var table;
    $(function() {
      if(table)
        table.destroy();
        
      table = $("#item_table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url":"{{url('item/list/api')}}",
                "datatype": "json",
                "data": function (data,json) {
                    var startDate = $('#min-date').val();
                    var endDate = $('#max-date').val();
                    data.startDate = startDate;
                    data.endDate = endDate;
                }
            },
            "responsive": true,
            "aaSorting": [],
            "columns": [
                { "data": "id" },
                { "data": "name"}, 
                { "data": "cat_name"}, 
                { 
                    data: function(data,type,full,meta){
                        var x = '';
                        if(data.images[0] != null){
                          var src= '{{url("uploads/items")}}/'+data.images[0].photo;
                          x += '<img class="img-thumbnail"  src="'+src+'" title="'+data.name+'">'
                        }
                        return x;
                    }
                }, 
                { "data": "hsn_no"},
                { data: function(data, type, full, meta){
                    return data.percent +'%';
                  }
                },
                { data: function(data, type, full, meta){
                    var dt=data.created_at;
                        dt=new Date(dt); 
                        var dd=dt.getDate();
                        var mm=dt.getMonth()+ 1;
                        var yyyy=dt.getFullYear();
                        var hh=dt.getHours();
                        var mi=dt.getMinutes();
                        var ss=dt.getSeconds();
                            var d = "AM";
                            var h = hh;
                            if (h >= 12) {
                            h = hh - 12;
                            d = "PM";
                            }
                            if (h == 0) {h = 12;}
                        var ac=dd+'-'+mm+'-'+yyyy;
                        return ac;
                    }
                },
                { 
                    data: function(data,type,full,meta){
                        var x =  '';
                        if(data.created_by_name != null){
                          x += data.created_by_name
                        }
                        if(data.created_by_name != null){
                            x += ' / ' +data.created_by_name
                        }
                        return x;
                    }
                }
                @if(Auth::user()->hasPermissionTo('item.edit') || Auth::user()->hasPermissionTo('item.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                ,{
                  data:function(data,type,full,meta){
                      
                      var x='';
                      @if(Auth::user()->hasPermissionTo('item.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      x +='<a onclick="return $(\'#'+data.id+'_item_edit_modal\').modal(\'show\');" class="btn btn-success btn-sm text-white"><i class="mdi mdi-pen"></i></a>';
                      @endif
                      @if(Auth::user()->hasPermissionTo('item.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      x +='&nbsp;<a onclick="return $(\'#'+data.id+'_item\').modal(\'show\');" class="btn btn-danger btn-sm text-white"><i class=" mdi mdi-delete-forever"></i></a>'
                      @endif
                      return x ;
                    }
                  }
                @endif
            ],
            "columnDefs": [
                { "orderable": false, "targets": 7},
                { "orderable": false, "targets": 3},
                { "orderable": false, "targets": 8}
            ],
            dom: 'Blfrtip',
            buttons: [
            {
                extend:'excelHtml5',
                className: 'btn-sm mb-4',
                exportOptions: {
                    columns: [ 0, 1, 2,4,5,6 ] 
                }
            },
            {
                extend:'pdfHtml5',
                className: 'btn-sm mb-4',
                exportOptions: {
                    columns: [0, 1, 2,4,5,6  ] //Your Column value those you want
                }
            }
            
            ]
        });
        jQuery('#item_form').validate({ // initialize the plugin
          rules: {
              name:{
                required:true,
              },
              category_id:{
                required:true,
              },
              hsn_id:{
                required:true,
              },
              gst_percent_id:{
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

        @foreach($items as $item)
          jQuery('#item_form_{{$item->id}}').validate({ // initialize the plugin
              rules: {
                name:{
                  required:true,
                },
                category_id:{
                  required:true,
                },
                hsn_id:{
                  required:true,
                },
                gst_percent_id:{
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
    $('.date-range-filter').change(function() {
        table.draw();
    });
  </script>
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12">
        @include('flash-msg')
  </div>
</div>


@include('item.list')
@include('item.create')
@foreach($items as $item)
    <div id="{{$item->id}}_item" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this item? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/item/'.$item->id)}}" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    @include('item.edit')
@endforeach
@endsection