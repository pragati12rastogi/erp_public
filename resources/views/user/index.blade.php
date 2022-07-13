@extends('layouts.master')
@section('title', 'User Creation')

@push('style')

@endpush

@push('custom-scripts')
{!! Html::script('/js/users.js') !!}
{!! Html::script('/js/area_district.js') !!}
<script>
    var table;
    $(function() {  

        if(table)
            table.destroy();

        table = $("#user_table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url":"{{url('user/list/api')}}",
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
                { "data": "name" },
                { "data": "role.name"}, 
                { "data": "firm_name"}, 
                { "data": "email"}, 
                { "data": "mobile"}, 
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
                        var x =  data.created_by_user.name;
                        if(data.updated_by_user != null){
                            x += ' / ' +data.updated_by_user.name
                        }
                        return x;
                    }
                }, 
                {
                    data:function(data,type,full,meta){
                        var url = "{{ url('quickupdate/user/status') }}/"+data.id;
                        var btn_class= 'btn-danger';
                        var btn_text = 'Deactive';
                        
                        if(data.status){
                            btn_class = 'btn-success';
                            btn_text = 'Active';
                        }

                        var str = '<form action="'+url+'" method="POST">{{csrf_field()}}<button type="submit" class="btn btn-xs '+btn_class+'">'+btn_text+'</button></form>';

                        return str;
                    }
                }
                
                <?php if(Auth::user()->hasPermissionTo('users.edit') || Auth::user()->hasPermissionTo('users.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)){ ?>
                ,{
                data:function(data,type,full,meta){
                    
                    var x='';
                    @if(Auth::user()->hasPermissionTo('users.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        x += '<a onclick="edit_users_modal('+data.id+')" class="btn btn-success btn-sm text-white"><i class="mdi mdi-pen"></i></a>';
                    @endif

                    @if(Auth::user()->hasPermissionTo('users.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        x += '&nbsp;<a onclick="return $(\'#'+data.id+'_user\').modal(\'show\');" class="btn btn-danger btn-sm text-white"><i class=" mdi mdi-delete-forever"></i></a>';
                    @endif
                    return x ;
                    }
                }
                <?php } ?>
            ],
            "columnDefs": [
                { "orderable": false, "targets": 8}
            ]
        });
    });

    function edit_users_modal(edit_id){
        var submit_edit_url = '{{url("users")}}/'+edit_id;
        var get_edit_url = submit_edit_url +'/edit';
        
        $.ajax({
            type:"GET",
            dataType:"JSON",
            url:get_edit_url,
            success:function(result){

                if(result != ''){
                    var inputs = result.user;
                    $('#user_form_upd').attr('action',inputs.edit_url);

                    $('#role_upd').val(inputs.role_name).trigger('change');
                    $('#name_upd').val(inputs.name);
                    $('#email_upd').val(inputs.email);
                    $('#mobile_upd').val(inputs.mobile);
                    $('#firm_name_upd').val(inputs.firm_name);
                    $('#gst_no_upd').val(inputs.gst_no);
                    $('#address_upd').val(inputs.address);
                    $('#bank_name_upd').val(inputs.bank_name);
                    $('#name_on_passbook_upd').val(inputs.name_on_passbook);
                    $('#ifsc_upd').val(inputs.ifsc);
                    $('#account_no_upd').val(inputs.account_no);
                    $('#pan_no_upd').val(inputs.pan_no);
                    $('#state_id_upd').val(inputs.state_id).trigger('change');
                    
                    if(inputs.p_image != ''){
                        $('#image_upd').attr('src',inputs.p_image);
                    }

                    
                    $("#user_edit_modal").modal('show');
                    
                    setTimeout(() => {
                        $('#district_id_upd').val(inputs.district).trigger('change')
                    }, 500);

                    setTimeout(() => {
                        $('#area_id_upd').val(inputs.area_id).trigger('change')
                    }, 1000);

                }else{
                    alert('some error occured, please refresh page and try again');
                }
                    
            },
            error:function(error){
                console.log(error.responseText);
            }
        })
        
    }

    $('.date-range-filter').change(function() {
        table.draw();
    });
</script>
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">
      
        @include('flash-msg')
      
    </div>
</div>

@include('user.list')
@include('user.add')
@include('user.edit')
@foreach($users as $user)
    <div id="{{$user->id}}_user" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this User? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/users/'.$user->id)}}" class="pull-right">
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