function get_district(state,inptype){
    var state_id = state.value;

    $.ajax({
        url:admin_url+'/state/district/api',
        dataType:"JSON",
        data:{'state_id':state_id},
        success:function(response){
            if(response.status == 'success'){
                
                var str = '<option value="">Select District</option>';

                $.each(response.data,function(ind,value){
                    
                    str += '<option value="'+value.id+'" >'+value.name+'</option>';
                })

                if(inptype=='upd'){
                    $("#district_id_upd").empty();
                    $("#district_id_upd").append(str);
                }else{
                    $("#district_id").empty();
                    $("#district_id").append(str);
                }
                
            }
        },
        error: function(error){
            console(error.responseText);
        }
    })
}

function get_area(district,inptype){
    var district_id = district.value;

    $.ajax({
        url:admin_url+'/district/area/api',
        dataType:"JSON",
        data:{'district_id':district_id},
        success:function(response){
            if(response.status == 'success'){
                
                var str = '<option value="">Select Area</option>';

                $.each(response.data,function(ind,value){
                    str += '<option value="'+value.id+'" >'+value.name+'</option>';
                })

                if(inptype=='upd'){
                    $("#area_id_upd").empty();
                    $("#area_id_upd").append(str);
                }else{
                    $("#area_id").empty();
                    $("#area_id").append(str);
                }
                
            }
        },
        error: function(error){
            console(error.responseText);
        }
    })
}